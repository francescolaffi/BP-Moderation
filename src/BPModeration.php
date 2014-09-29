<?php

namespace BPModeration;

use BPModeration\Service\LifeCycle;
use BPModeration\Model\Options;

/**
 * Class BPModeration
 * @package BPModeration
 */
class BPModeration
{
    /**
     * Plugin Version
     */
    const VERSION = '1.0-dev';

    private $options;

    private $lifecycle;

    public function __construct()
    {
        $this->options = new Options();

        $this->loadL10n();

        $this->lifecycle = new LifeCycle($this->options);

        add_action('init', array($this, 'init'));
    }

    private function loadL10n()
    {

    }

    public function init()
    {
        if(!$this->lifecycle->checkInstalled()){
            return;
        }
    }
}