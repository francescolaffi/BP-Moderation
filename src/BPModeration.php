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

    public function __construct()
    {
        $this->options = new Options();

        new LifeCycle($this->options);
    }
}