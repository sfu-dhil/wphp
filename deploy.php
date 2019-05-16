<?php

namespace Deployer;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;

require 'recipe/symfony3.php';

inventory('app/config/deploy.yml');

$settings = Yaml::parseFile('app/config/deploy.yml');
foreach ($settings['.settings'] as $key => $value) {
    set($key, $value);
}

$app = get('application');
if(file_exists("deploy.{$app}.php")) {
    require "deploy.{$app}.php";
}

task('dhil:precheck', function(){
    $out = runLocally('git cherry -v');
    if ($out !== '') {
        $commits = count(explode("\n", $out));
        writeln("<error>Warning: {$commits} unpublished commits will not be included in the deployment.</error>");
    }
});

task('dhil:deploy:quick', function() {
    writeln(run('cd {{ current_path }} && git pull origin master'));
    writeln(run('cd {{ current_path }} && git submodule foreach git pull origin master'));
    writeln(run('{{bin/php}} {{bin/console}} cache:clear --env=prod'));
});

option('skip-tests', null, InputOption::VALUE_NONE, 'Skip testing. Probably a bad idea.');
task('dhil:phpunit', function() {
    if (input()->getOption('skip-tests')) {
        writeln('Skipped');
        return;
    }
    $output = run('cd {{ release_path }} && ./vendor/bin/phpunit', ['timeout' => null]);
    writeln($output);
})->desc('Run phpunit.');

task('dhil:clear:test-cache', function() {
    $output = run('{{bin/php}} {{bin/console}} cache:clear --env=test');
    writeln($output);
});

task('dhil:test', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:vendors',
    'dhil:clear:test-cache',
    'dhil:phpunit',
])->desc('Run test suite on server in a clean environment.');
after('dhil:test', 'deploy:unlock');

task('dhil:bower', function() {
    $output = run('cd {{ release_path }} && bower -q install');
    writeln($output);
    if(file_exists('package.json')) {
        $output = run('cd {{ release_path }} && npm -q install --prefix web/npm');
        writeln($output);
    }
})->desc('Install bower dependencies.');

task('dhil:sphinx', function() {
    if (file_exists('docs')) {
        $user = get('user');
        $host = get('hostname');
        $become = get('become');

        runLocally('/usr/local/bin/sphinx-build docs/source web/docs/sphinx');
        runLocally("rsync -av -e 'ssh' --rsync-path='sudo -u $become rsync' ./web/docs/ $user@$host:{{release_path}}/web/docs", ['timeout' => null]);
    }
})->desc('Build sphinx docs locally and upload to server.');

task('dhil:db:backup', function() {
    $user = get('user');
    $become = get('become');
    $app = get('application');

    set('become', $user); // prevent sudo -u from failing.
    $date = date('Y-m-d');
    $current = get('release_name');
    $file = "/home/{$become}/{$app}-{$date}-r{$current}.sql";
    run("sudo mysqldump {$app} -r {$file}");
    run("sudo chown {$become} {$file}");
    set('become', $become);
})->desc('Backup the mysql database.');

task('dhil:db:migrate', function(){
    $output = run('cd {{ release_path }} && ./bin/console doctrine:migrations:migrate --no-interaction');
    writeln($output);
});

task('dhil:db:fetch', function() {
    $user = get('user');
    $become = get('become');
    $app = get('application');
    $stage = get('stage');

    $date = date('Y-m-d');
    $current = get('release_name');

    set('become', $user); // prevent sudo -u from failing.
    $file = "/home/{$user}/{$app}-{$date}-{$stage}-r{$current}.sql";
    run("sudo mysqldump {$app} -r {$file}");
    run("sudo chown {$user} {$file}");
    // set('become', $become);

    download($file, basename($file));
    writeln("Downloaded database dump to " . basename($file));
})->desc('Make a database backup and download it.');

task('success', function() {
    $target = get('target');
    $release = get('release_name');
    $host = get('hostname');
    $path = get('site_path');

    writeln("Successfully deployed {$target} release {$release}");
    writeln("Visit http://{$host}{$path} to check.");
});

task('deploy', [
    'deploy:info',
    'dhil:precheck',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:vendors',
    'dhil:clear:test-cache',
    'dhil:phpunit',
    'deploy:assets:install',
    'deploy:cache:clear',
    'deploy:writable',
    'dhil:db:backup',
    'dhil:db:migrate',
    'dhil:sphinx',
    'dhil:bower',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');
after('deploy:failed', 'deploy:unlock');
after('deploy', 'success');
