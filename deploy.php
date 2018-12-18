<?php

namespace Deployer;

require 'recipe/symfony4.php';

set('shared_dirs', ['var/log', 'var/sessions']);
set('writable_dirs', ['var/log', 'var/cache', 'var/sessions']);

// Configuration
set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('repository', 'git@github.com:webstylecenter/Homepage.git');

// Servers
host('vps.petervdam.nl')
    ->user('peter')
    ->forwardAgent()
    ->stage('production')
    ->set('prefix', 'prod')
    ->set('deploy_path', '/home/peter/domains/feednews.me');


task('build', function () {
    set('deploy_path', __DIR__ . '/.build');
    invoke('deploy:unlock');
    invoke('deploy:info');
    invoke('deploy:prepare');
    invoke('deploy:lock');
    invoke('deploy:release');
    invoke('deploy:update_code');
    invoke('deploy:symlink');
})->local();

task('release', [
    'deploy:info',
    'deploy:prepare',
    'deploy:release',
    'upload',
    'deploy:shared',
    'deploy:vendors',
    'update_database',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
]);

task('deploy', [
    'build',
    'release',
    'cleanup',
    'success'
]);

// =========RECIPES========== \\
task('update_database', function () {
    run('{{bin/console}} doctrine:schema:update --force');
})->desc('Update database schema');

task('upload', function () {
    upload(__DIR__ . "/.build/current/", '{{release_path}}');
});
