symfony-users-playground
========================

User management system playground based on Symfony 2.5

Config Vagrant in your local machine
Download composer to the root project folder
Mount your project root folder cd `local/path/to/your/project`
Start Vagrant VM `vagrant up` (it will take some time)
SSH to Vagrant `vagrant ssh`
Update composer `php composer.phar update`
Config MySQL to Vagrant or to your local machine `app/config/parameters.yml`
Create database schema `php app/console doctrine:schema:create`
Create default user with super admin role `php app/console fos:user:create admin --super-admin [username] [password]`
Try to access `http://playground.dev` via your browser
