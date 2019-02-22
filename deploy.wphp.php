<?php

namespace Deployer;

task('wphp:db:fetch', function(){
    $stage = get('stage');
    $date = date('Y-m-d');
    $user = get('user');
    $app = get('application');
    $host = get('hostname');

    $opt = implode(' ', array_map(function($t) use ($app) {
        return "--ignore-table={$app}.{$t}";
    }, get('ignore_tables')));

    $base = "/home/{$user}";
    $tblFile = $base . "/{$app}-{$stage}-tables-{$date}";
    $dataFile = $base . "/{$app}-{$stage}-data-{$date}";

    run("mysqldump --no-data -r {$tblFile}.sql {$opt} {$app}");
    run("gzip -f -9 {$tblFile}.sql");
    $local = basename($tblFile) . '.sql.gz';
    runLocally("scp {$host}:{$tblFile}.sql.gz {$local}");

    run("mysqldump --no-create-info -r {$dataFile}.sql {$opt} {$app}");
    run("gzip -f -9 {$dataFile}.sql");
    $local = basename($dataFile) . '.sql.gz';
    runLocally("scp {$host}:{$dataFile}.sql.gz {$local}");

})->desc('Download the backend copy of WPHP.');

task('wphp:db:import', function(){
    $stage = get('stage');
    $date = date('Y-m-d');
    $app = get('application');
    $db = get('application') . 'tmp';
    $base = getcwd();
    $opts = ['timeout' => null];

    $tblFile = $base . "/{$app}-{$stage}-tables-{$date}";
    $dataFile = $base . "/{$app}-{$stage}-data-{$date}";

    if(file_exists("{$tblFile}.sql.gz")) {
        runLocally("gunzip {$tblFile}.sql.gz");
    }
    if(file_exists("{$dataFile}.sql.gz")) {
        runLocally("gunzip {$dataFile}.sql.gz");
    }

    runLocally("mysql -e 'DROP DATABASE IF EXISTS {$db}'", $opts);
    runLocally("mysql -e 'CREATE DATABASE {$db}'", $opts);
    // SOURCE instead of shell redirection to get line numbers in error messages.
    runLocally("mysql {$db} -e 'SOURCE {$tblFile}.sql' ", $opts);
    runLocally("mysql {$db} -e 'SOURCE {$base}/data/migration-tables.sql' ", $opts);
    runLocally("mysql {$db} -e 'SOURCE {$dataFile}.sql' ", $opts);
    runLocally("mysql {$db} -e 'SOURCE {$base}/data/migration-data.sql' ", $opts);
    runLocally("mysql {$db} -e 'SOURCE {$base}/data/migration.sql' ", $opts);

})->desc('Import the WPHP arts db into a temp db.');

task('wphp:db:migrate', [
    'wphp:db:fetch',
    'wphp:db:import',
]);