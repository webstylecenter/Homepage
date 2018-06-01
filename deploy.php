<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project repository
set('repository', 'git@github.com:webstylecenter/Homepage.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('feednews.me')
    ->set('deploy_path', '/home/peter/domains/feednews.me')
    ->user('name')
    ->forwardAgent(true)
    ->addSshOption('StrictHostKeyChecking', 'no');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

