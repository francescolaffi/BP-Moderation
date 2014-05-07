<?php
/*
Plugin Name: BuddyPress Moderation
Plugin URI: http://buddypress.org/community/groups/bp-moderation/
Description: Plugin for moderation of buddypress communities, it adds a 'report this' link to every content so members can help admin finding inappropriate content.
Version: 1.0-dev
Author: Francesco Laffi
Author URI: http://flweb.it
License: GPL2
Text Domain: bp-moderation
Domain Path: /lang/
*/

/*  Copyright 2014  Francesco Laffi  (email : francesco.laffi@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * bp-moderation plugin loader
 *
 * the loader must maintain compatibility with:
 * - WP: 3.2+
 * - PHP: 5.2.4+
 */
class BPModerationLoader
{
    /**
     * plugin file path
     */
    const FILE = __FILE__;

    /**
     * Required WordPress version
     */
    const MIN_PHP = '5.3.2';

    /**
     * Required WordPress version
     */
    const MIN_WP = '3.7';

    /**
     * @var array
     */
    private $compatibilityProblems;

    /**
     * bootstrap bp-moderation plugin
     *
     * @return bool
     */
    public function bootstrap()
    {
        if (!$this->checkCompatibility()) {
            add_action('admin_notices', array($this, 'compatibilityNotice'));
            return false;
        }

        spl_autoload_register(array($this, 'autoload'));

        return true;
    }

    /**
     * check dependencies
     *
     * @return bool
     */
    private function checkCompatibility()
    {
        $this->compatibilityProblems = array();
        // check php
        if (version_compare(self::MIN_PHP, phpversion(), '>')) {
            $this->compatibilityProblems['PHP'] = array('required' => self::MIN_PHP, 'installed' => phpversion());
        }
        // check wp
        if (version_compare(self::MIN_WP, $GLOBALS['wp_version'], '>')) {
            $this->compatibilityProblems['WordPress'] = array('required' => self::MIN_WP, 'installed' => $GLOBALS['wp_version']);
        }
        return empty($this->compatibilityProblems);
    }

    /**
     * print admin compatibility notice
     */
    public function compatibilityNotice()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
        echo '<div class="error"><p>';
        echo 'BP Moderation is not active, please fix these compatibility problems:';
        foreach ($this->compatibilityProblems as $name => $info) {
            echo "<br> - $name: required version is {$info['required']}, installed version is {$info['installed']}";
        }
        echo '</p></div>';
    }

    /**
     * class autoloader
     *
     * @param string $class class name
     */
    public function autoload($class)
    {
        if ('BPModeration\\' === substr($class, 0, 14)) {
            $relPath = str_replace('\\', '/', substr($class, 14));
            require __DIR__ . '/src' . $relPath . '.php';
        }
    }
}

/***** Load bp-moderation plugin *****/

$loader = new BPModerationLoader;

if ($loader->bootstrap()) {

    /**
     * Get BPModeration instance
     *
     * @return \BPModeration\BPModeration
     */
    function bpModeration()
    {
        static $instance;

        if (!$instance) {
            // create by name to avoid php 5.2 parse error
            $class = '\BPModeration\BPModeration';
            $instance = new $class;
        }
        return $instance;
    }

    bpModeration();
}

unset($loader);

