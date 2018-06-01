<?php

namespace Deployer;

require 'recipe/symfony.php';

// Configuration
set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('repository', 'git@github.com:webstylecenter/Homepage.git');

set('bin_dir', 'bin');
set('var_dir', 'var');

set('shared_dirs', ['var/logs']);
set('writable_dirs', ['var/cache', 'var/logs']);

// Servers
host('feednews.me')
    ->user('peter')
    ->forwardAgent()
    ->stage('production')
    ->set('deploy_path', '/home/peter/domains/feednews.me')
    ->set('prefix', 'prod');

task('build', function () {
    set('deploy_path', __DIR__ . '/.build');
    invoke('deploy:prepare');
    invoke('deploy:release');
    invoke('deploy:update_code');
    invoke('deploy:symlink');
})->local();

task('release', [
    'deploy:prepare',
    'deploy:release',
    'upload',
    'deploy:clear_paths',
    'deploy:create_cache_dir',
    //'copy_parameters',
    'deploy:vendors',
    'update_database',
    'deploy:shared',
    'deploy:assets',
    'deploy:vendors',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:writable',
    'deploy:symlink',
]);

task('deploy', [
    'build',
    'release',
    'cleanup',
    'success'
]);

// =========RECIPES========== \\

task('update_database', function () {
    run('{{bin/php}} {{bin/console}} doctrine:migrations:migrate {{console_options}}');
})->desc('Update database schema');

//task('copy_parameters', function () {
//    run('/usr/bin/cp -rf {{release_path}}/.env.{{prefix}} {{release_path}}/.env');
//})->desc('Copy parameters.yml to build target');


task('upload', function () {
    upload(__DIR__ . "/.build/current/", '{{release_path}}');
});
