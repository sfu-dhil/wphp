<?php

namespace Deployer;

require 'deployment/symfony44.php';

/**
 * Create a MySQL database backup and download it from the server.
 */
task('dhil:db:fetch', function () {
    $user = get('user');
    $become = get('become');
    $app = get('application');
    $stage = get('stage');

    $date = date('Y-m-d');
    $current = get('release_name');

    set('become', $user); // prevent sudo -u from failing.
    $file = "/home/{$user}/{$app}-{$date}-{$stage}-r{$current}.sql";
    run("sudo mysqldump --ignore-table={$app}.estc_fields {$app} -r {$file}");
    run("sudo chown {$user} {$file}");

    download($file, basename($file));
    writeln('Downloaded database dump to ' . basename($file));
})->desc('Make a database backup and download it.');

