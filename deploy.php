<?php

namespace Deployer;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;

require 'recipe/symfony3.php';

inventory('app/config/deploy.yml');

$settings = Yaml::parseFile('app/config/deploy.yml');
foreach($settings['.settings'] as $key => $value) {
    set($key, $value);
}

task('dhil:ckeditor', function(){
    $output = run('{{bin/php}} {{bin/console}} ckeditor:install');
    writeln($output);
});

option('skip-tests', null, InputOption::VALUE_NONE, 'Skip testing. Probably a bad idea.');
task('dhil:phpunit', function() {
	if(input()->getOption('skip-tests')) {
		writeln('Skipped');
		return;
	}
    $output = run('cd {{ release_path }} && ./vendor/bin/phpunit', ['timeout' => null]);
    writeln($output);
})->desc('Run phpunit.');

task('dhil:test', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:vendors',
    'dhil:phpunit',
])->desc('Run test suite on server in a clean environment.');
after('dhil:test', 'deploy:unlock');

task('dhil:bower', function() {
    $output = run('cd {{ release_path }} && bower -q install');
    writeln($output);
})->desc('Install bower dependencies.');

task('dhil:sphinx', function(){
    if(file_exists('docs')) {
        $user = get('user');
        $host = get('hostname');
        $become = get('become');

        runLocally('/usr/local/bin/sphinx-build docs/source web/docs/sphinx');
        runLocally("rsync -av -e 'ssh' --rsync-path='sudo -u $become rsync' ./web/docs/ $user@$host:{{release_path}}/web/docs", ['timeout' => null]);
    }
})->desc('Build sphinx docs locally and upload to server.');

task('dhil:download:images', function(){
    $user = get('user');
    $host = get('hostname');
    $become = get('become');

    runLocally("rsync -av -e 'ssh' --rsync-path='sudo -u $become rsync' $user@$host:{{release_path}}/web/images/clippings/ ./web/images/clippings", ['timeout' => null]);
})->desc('Download clipping images from server.');

task('dhil:upload:images', function(){
    $user = get('user');
    $host = get('hostname');
    $become = get('become');

    runLocally("rsync -av -e 'ssh' --rsync-path='sudo -u $become rsync' ./web/images/clippings/ $user@$host:{{release_path}}/web/images/clippings", ['timeout' => null]);
})->desc('Upload clipping images to server.');

option('update-db', null, InputOption::VALUE_NONE, 'Force the action to run');
task('dhil:db:update', function() {
    $update = run('{{bin/php}} {{bin/console}} doctrine:schema:update --dump-sql');
    writeln($update);
    if (input()->getOption('update-db')) {
    	writeln("Updating database.");
        $result = run('{{bin/php}} {{bin/console}} doctrine:schema:update --force');
        writeln($result);
    } else {
		writeln("Database updates are not automatically applied. Use dhil:db:update --update-db to apply.");
	}
})->desc('Run a docctrine:schema:update.');

task('dhil:db:backup', function(){
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

task('dhil:db:fetch', function(){
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
    set('become', $become);

    download($file, basename($file));
    writeln("Downloaded database dump to " . basename($file));
})->desc('Make a database backup and download it.');

task('success', function(){
    $target = get('target');
    $release = get('release_name');
    $host = get('hostname');
    $path = get('site_path');

    writeln("Successfully deployed {$target} release {$release}");
    writeln("Visit http://{$host}{$path} to check.");
});

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:vendors',
    'dhil:phpunit',
    'dhil:ckeditor',
    'deploy:assets:install',
    'deploy:cache:clear',
    'deploy:writable',
    'dhil:db:backup',
    'dhil:db:update',
    'dhil:sphinx',
    'dhil:bower',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');
after('deploy:failed', 'deploy:unlock');
after('deploy', 'success');
