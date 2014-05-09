<?php

namespace BPModeration\Model;

/**
 * Plugin Options Model
 *
 * manage plugin options as a single db options
 */
class Options
{
	const OPT_NAME = 'bp_moderation_options';

	private $options;

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{
		$this->options = get_site_option(self::OPT_NAME) ? : array();
	}

	public function save()
	{
		return update_site_option(self::OPT_NAME, $this->options);
	}

	public function get($key)
	{
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	public function set($key, $value)
	{
		$this->options[$key] = $value;
	}

	public function has($key)
	{
		return array_key_exists($key, $this->options);
	}

	public function remove($key)
	{
		unset($this->options[$key]);
	}

    public function delete() {
        return delete_site_option(self::OPT_NAME);
    }
}