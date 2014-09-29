<?php


namespace BPModeration\Service;

use BPModeration\Model\Options;

/**
 * Plugin Life Cycle Service
 *
 * helper for plugin activation/deactivation/cleanup/upgrades ...
 */
class LifeCycle
{
    /**
     * DB and Options Version
     */
    const DB_VERS = 2;

    private $options;

    public function __construct(Options $options)
    {
        $this->options = $options;

        register_activation_hook(\BPModerationLoader::FILE, array($this, 'activation'));
        register_deactivation_hook(\BPModerationLoader::FILE, array($this, 'deactivation'));
    }

    public function activation()
    {
        $this->checkInstalled();
    }

    public function deactivation()
    {
        //noop
    }

    public function checkInstalled()
    {
        $current = $this->checkVersion();

        if(self::DB_VERS === $current) {
            return true;
        }

        $installer = new Installer($this->options, $GLOBALS['wpdb']);

        if (null === $current) {
            $r = $installer->install();
        } else {
            $r = $installer->upgrade($current);
        }

        if (true !== $r) {
            return false;
        }

        $this->options->set('version', self::DB_VERS);
        return $this->options->save();
    }

    private function checkVersion()
    {
        //todo: convert old versions or fetch
        return 0;
    }
}