Exec { path => "/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"}

if $server_config == undef {
  $server_config = hiera('server', false)
}

user { 'vagrant_user':
  name   => vagrant,
  ensure => present,
  groups => 'www-data'
}

ensure_packages(['augeas-tools'])

class { 'git': }

class { 'composer': }

class { 'nginx': }

nginx::resource::vhost { 'playground.dev':
  ensure      => present,
  server_name => [
    'playground.dev',
    'www.playground.dev'
  ],
  index_files => [
    'app_dev.php',
    'app.php',
    'index.php',
    'index.html'
  ],
  listen_port => 80,
  www_root    => '/var/www/web/',
  try_files   => ['$uri', '$uri/', '/app_dev.php?$args'],
}

nginx::resource::location { 'playground.dev-php':
  ensure              => 'present',
  index_files         => [
    'app_dev.php',
    'app.php',
    'index.php',
    'index.html'
  ],
  vhost               => 'playground.dev',
  location            => '~ \.php$',
  proxy               => undef,
  try_files           => ['$uri', '$uri/', '/app_dev.php?$args'],
  www_root            => '/var/www/web/',
  location_cfg_append => {
    'fastcgi_split_path_info'   => '^(.+\.php)(/.+)$',
    'fastcgi_param'             => 'PATH_INFO $fastcgi_path_info',
    'fastcgi_param '            => 'PATH_TRANSLATED $document_root$fastcgi_path_info',
    'fastcgi_param  '           => 'SCRIPT_FILENAME $document_root$fastcgi_script_name',
    'fastcgi_pass'              => 'unix:/var/run/php5-fpm.sock',
    'fastcgi_index'             => 'app_dev.php',
    'fastcgi_buffer_size'       => '128k',
    'fastcgi_buffers'           => '4 256k',
    'fastcgi_busy_buffers_size' => '256k',
    'include'                   => 'fastcgi_params',
    'proxy_buffer_size'         => '128k',
    'proxy_buffers'             => '4 256k',
    'proxy_busy_buffers_size'   => '256k'
  },
  notify              => Class['nginx::service'],
}

nginx::resource::vhost { 'xhprof.playground.dev':
  ensure       => present,
  server_name  => ['xhprof.playground.dev'],
  index_files  => ['index.php', 'index.html'],
  listen_port  => 80,
  www_root     => '/var/www/vendor/facebook/xhprof/xhprof_html/',
  try_files    => ['$uri', '$uri/', '/index.php?$args'],
}

nginx::resource::location { 'xhprof.playground.dev-php':
  ensure              => 'present',
  index_files         => ['index.php', 'index.html'],
  vhost               => 'xhprof.playground.dev',
  location            => '~ \.php$',
  proxy               => undef,
  try_files           => ['$uri', '$uri/', '/index.php?$args'],
  www_root            => '/var/www/vendor/facebook/xhprof/xhprof_html/',
  location_cfg_append => {
    'fastcgi_split_path_info' => '^(.+\.php)(/.+)$',
    'fastcgi_param'           => 'PATH_INFO $fastcgi_path_info',
    'fastcgi_param '          => 'PATH_TRANSLATED $document_root$fastcgi_path_info',
    'fastcgi_param  '         => 'SCRIPT_FILENAME $document_root$fastcgi_script_name',
    'fastcgi_pass'            => 'unix:/var/run/php5-fpm.sock',
    'fastcgi_index'           => 'index.php',
    'include'                 => 'fastcgi_params'
  },
  notify              => Class['nginx::service'],
}

class { '::mysql::server':
  root_password => 'root',
}

class { 'php':
  version             => 'latest',
  package             => 'php5-fpm',
  service             => 'php5-fpm',
  service_autorestart => false,
  config_file         => '/etc/php5/fpm/php.ini',
}

service { 'php5-fpm':
  ensure     => running,
  enable     => true,
  hasrestart => true,
  hasstatus  => true,
  require    => Package['php5-fpm']
}

php::module {
  [
    'mysql',
    'cli',
    'curl',
    'intl',
    'gd',
    'mcrypt',
    'common',
    'xhprof',
    'xdebug'
  ]:
}->
exec { 'custom':
  command => 'cp /vagrant/.vagrant/files/custom.ini /etc/php5/mods-available/ && php5enmod custom',
  returns => [0, 1],
  notify  => Service['php5-fpm'],
  require => Class['php']
}

file_line { 'auto-cd':
   path => '/home/vagrant/.bashrc',
   line => "cd /var/www",
}

class { 'nodejs':
  version  => 'stable'
}

package { 'bower':
  provider => npm,
  require  => Class['nodejs']
}
