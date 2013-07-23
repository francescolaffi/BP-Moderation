#!/usr/bin/env ruby
#^syntax detection

Vagrant.configure("2") do |config|
  config.vm.box = 'precise32'
  config.vm.box_url = 'http://files.vagrantup.com/precise32.box'

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # config.vm.network :forwarded_port, guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network :private_network, ip: "192.168.33.15"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network :public_network

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  config.vm.synced_folder ".", "/vagrant",
    :nfs => (RUBY_PLATFORM =~ /linux/ or RUBY_PLATFORM =~ /darwin/)

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  
  config.vm.provider :virtualbox do |vb|
    # Don't boot with headless mode
    # vb.gui = true
  
    # Use VBoxManage to customize the VM. For example to change memory:
    vb.customize ["modifyvm", :id, "--memory", "512"]
  end
  
  # View the documentation for the provider you're using for more
  # information on available options.

  # Enable provisioning with chef solo, specifying a cookbooks path, roles
  # path, and data_bags path (all relative to this Vagrantfile), and adding
  # some recipes and/or roles.
  
  config.vm.provision :chef_solo do |chef|
    # chef debug level, start vagrant like this to debug:
    # $ CHEF_LOG_LEVEL=debug vagrant <provision or up>
    chef.log_level = ENV['CHEF_LOG_LEVEL'] || "info"

    chef.add_recipe 'wp'
  
    chef.json = {
      'wp' => {
        'installs' => {
          'bpmod-bp17.dev' => {
          	'title' => 'bpmod bp17 singlesite test',
            'plugins' => {
              'buddypress' => {
                'zip' => 'http://downloads.wordpress.org/plugin/buddypress.1.7.3.zip',
                'active' => true
              },
              'bp-moderation' => {
                'source' => '/vagrant',
                'active' => true
              }
            }
          },
          'bpmod-bp17ms.dev' => {
          	'title' => 'bpmod bp17 multisite test',
          	'network' => {
          		'title' => 'bpmod multisite test network'	
          	},
            'plugins' => {
              'buddypress' => {
                'zip' => 'http://downloads.wordpress.org/plugin/buddypress.1.7.3.zip',
              	'network' => true,
                'active' => true
              },
              'bp-moderation' => {
                'source' => '/vagrant',
              	'network' => true,
                'active' => true
              }
            }
          },
          'bpmod-bp18.dev' => {
          	'title' => 'bpmod bp17 singlesite test',
            'plugins' => {
              'buddypress' => {
                'version' => '1.8',
                'active' => true,
              },
              'bp-moderation' => {
                'source' => '/vagrant',
                'active' => true
              }
            }
          },
          'bpmod-bp18ms.dev' => {
          	'title' => 'bpmod multisite bp 1.8-beta test',
          	'network' => {
          		'title' => 'bpmod multisite bp 1.8-beta test network'
          	},
            'plugins' => {
              'buddypress' => {
                'version' => '1.8',
              	'network' => true,
                'active' => true
              },
              'bp-moderation' => {
                'source' => '/vagrant',
              	'network' => true,
                'active' => true
              }
            }
          },
        },
        'globals' => {
          'theme' => 'bp-default',
          'clean-install' => true,
          'rewrite' => {
            'structure' => '/%year%/%monthnum%/%day%/%postname%/'
          },
          'commands' => [
            'option set avatar_default identicon',
            'user create user user@example.com --role=editor --user_pass=pw'
          ]
        },
        'user' => 'vagrant',
        'group' => 'vagrant',
      },
      'mysql' => {
        'server_root_password'   => 'iloverandompasswordsbutthiswilldo',
        'server_repl_password'   => 'iloverandompasswordsbutthiswilldo',
        'server_debian_password' => 'iloverandompasswordsbutthiswilldo'
      },
      'apache' => {
        'user' => 'vagrant',
        'group' => 'vagrant',
      }
    }
  end
end
