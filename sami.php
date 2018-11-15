<?php

use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;
use Sami\Parser\Filter\TrueFilter;

$dir = __DIR__;

$iterator = Finder::create()
        ->files()
        ->name('*.php')
        ->exclude('Resources')
        ->exclude('Tests')
        ->in($dir . '/src');

$options = array(
    // 'theme' => 'symfony',
    'title' => 'Women\'s Print History Project | Internals | DHIL',
    'build_dir' => $dir . '/web/docs/api',
    'cache_dir' => $dir . '/var/cache/sami',
    'default_opened_level' => 2,
    'include_parent_data' => true,
    'insert_todos' => true,
    'sort_class_properties' => true,
    'sort_class_methods' => true,
    'sort_class_constants' => true,
    'sort_class_traits' => true,
    'sort_class_interfaces' => true,
);

$sami = new Sami($iterator, $options);
$sami['filter'] = function(){
    return new TrueFilter();
};
return $sami;
