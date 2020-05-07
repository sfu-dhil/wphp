<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Sami;
use Symfony\Component\Finder\Finder;

$dir = __DIR__;

$config = [
    //    'theme'                => 'symfony',
    'title' => 'Women\'s Print History Project Internal API',
    'build_dir' => $dir . '/web/docs/api',
    'cache_dir' => $dir . '/var/cache/sami',
    'remote_repository' => new GitHubRemoteRepository('ubermichael/wphp', $dir),
    'default_opened_level' => 2,
];

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in('src')
;

return new Sami($iterator, $config);
