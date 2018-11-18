<?php

use Sami\Sami;
use Sami\RemoteRepository\AbstractRemoteRepository;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ .'/source')

;

return new Sami($iterator, [
    'title' => 'WebAuthn PHP server documentation',
    'build_dir' => dirname(__DIR__) . '/webauthn',
    'cache_dir' => __DIR__ . '/cache',
    'remote_repository'  => new class("madwizard-thomas", __DIR__ . '/source') extends AbstractRemoteRepository {
        public function getFileUrl($projectVersion, $relativePath, $line)
        {
            $relativePath = str_replace('\\', '/', $relativePath);
            if (!preg_match('~^/([^/]+)(/.+)$~', $relativePath, $m))
                return '';

            $relativePath = $m[2];
            $reposName = $this->name . "/" . $m[1];
            $url = 'https://github.com/'.$reposName.'/blob/'.str_replace('\\', '/', $projectVersion.$relativePath);
            if (null !== $line) {
                $url .= '#L'.(int) $line;
            }
            return $url;
        }
    }
]);
