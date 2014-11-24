symfony-users-playground
========================

User management system playground based on Symfony 2.5

1. Config Vagrant in your local machine
1. Download composer to the root project folder
1. Mount your project root folder cd `local/path/to/your/project`
1. Start Vagrant VM `vagrant up` (it will take some time)
1. SSH to Vagrant `vagrant ssh`
1. Install components via bower `bower install`
1. Update composer `php composer.phar update`
1. Config MySQL to Vagrant or to your local machine `app/config/parameters.yml`
1. Create database schema `php app/console doctrine:schema:create`
1. Create default user with super admin role `php app/console fos:user:create --super-admin`
1. Try to access `http://playground.dev` via your browser
