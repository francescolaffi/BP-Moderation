<?php


namespace BPModeration\Service;


use BPModeration\Model\Options;

class Installer
{
    private $options;

    private $wpdb;

    public function __construct(Options $options, \wpdb $wpdb)
    {
        $this->options = $options;
        $this->wpdb = $wpdb;
    }

    public function install()
    {
        return false;
    }

    public function upgrade($currentVersion)
    {
        return false;
    }

    public function cleanup()
    {
        $dropSql = sprintf('DROP TABLE IF EXISTS `%s`, `%s`', '');
        $this->wpdb->query($dropSql);

        $this->options->delete();
    }
}