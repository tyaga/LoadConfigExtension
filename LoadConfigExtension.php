<?php

namespace Tyaga\Extension;

use Silex\Application;
use Silex\ExtensionInterface;

class LoadConfigExtension implements ExtensionInterface {
    public function register(Application $app) {
	    $app['autoloader']->registerPrefix('sfYaml', __DIR__.'/vendor/yaml/lib');

	    $app['config'] = $app->share(function () use($app) {
			return new ConfigLoader($app['loadconfig.load']);
        });
    }
}

class ConfigLoader implements \ArrayAccess {
	private $values;

	public function __construct($config_paths) {
		foreach ($config_paths as $key=>$path) {
			$this->values[$key] = \sfYaml::load(file_get_contents($path));
		}
	}

	function offsetSet($id, $value) {
	    $this->values[$id] = $value;
	}

	function offsetGet($id) {
	    return $this->values[$id];
	}

	function offsetExists($id) {
	    return isset($this->values[$id]);
	}
	function offsetUnset($id) {
	    unset($this->values[$id]);
	}


}