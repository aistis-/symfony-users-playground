VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "puppetlabs/ubuntu-14.04-64-puppet"

  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.synced_folder "./", "/var/www", type: "nfs"

  config.vm.hostname = "playground.dev"

  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--memory", "1024"]
    vb.customize ["setextradata", :id, "--VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root", "1"]
  end

  config.vm.provision :shell, :path => ".vagrant/shell/install.sh"

  config.vm.provision "puppet" do |puppet|
    puppet.module_path = ".vagrant/puppet/modules"
    puppet.manifests_path = ".vagrant/puppet"
    puppet.manifest_file = "manifest.pp"
    puppet.options = [
      "--verbose",
      "--debug",
      "--hiera_config /vagrant/.vagrant/puppet/hiera.yml"
    ]
  end

  config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

end
