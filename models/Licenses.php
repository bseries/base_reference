<?php
/**
 * Copyright 2017 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_reference\models;

use Exception;
use Composer\Spdx\SpdxLicenses;
use ReflectionClass;

class Licenses extends \base_core\models\Base {

	protected $_meta = [
		'connection' => false
	];

	protected static $_licenses = null;

	// Initializer.
	public static function init() {
		static::$_licenses = new SpdxLicenses();

		static::finder('first', function($params, $next) {
			if (!isset($params['options']['conditions']['name'])) {
				throw Exception("Licenses first-finder needs `'name'` as condition.");
			}
			$name = $params['options']['conditions']['name'];

			$result = static::$_licenses->getLicenseByIdentifier($name);
			if (!$result) {
				return false;
			}

			$url = $result[2];

			if (strpos($name, 'CC') === 0) {
				// Preserve CC short forms according to
				// https://creativecommons.org/licenses/
				//
				// CC-BY-SA-3.0 -> CC BY-SA 3.0
				// CC0-1.0 -> CC0 1.0
				$pos = strpos($name, '-');
				if ($pos !== false) {
					$name = substr_replace($name, ' ', $pos, 1);
				}
				$rpos = strrpos($name, '-');
				if ($rpos !== false) {
					$name = substr_replace($name, ' ', $rpos, 1);
				}

				// Use custom urls for CC references
				$ccUrl = function ($name) {
					$baseUrl = 'https://creativecommons.org/';
					if (strpos($name, 'CC0') === 0) {
						return $baseUrl . 'publicdomain/zero/1.0/legalcode';
					}
					$type = strtolower(substr($name, 3, -4));
					$version = substr($name, -3);
					return $baseUrl . 'licenses/' . $type . '/' . $version . '/legalcode';
				};

				$url = $ccUrl($name);
			}

			// Remap to our data structure, pretend as if this is a database
			// result. We might later turn this into a real entity object.
			return static::create([
				'name' => $name,
				'title' => $result[0],
				'is_osi_certified' => $result[1],
				'is_deprecated' => $result[3],
				'url' => $url
			]);
		});

		// FIXME Once https://github.com/composer/spdx-licenses/pull/18 has been
		// merged use that new method.
		static::finder('list', function($params, $next) {
			$results = [];

			$licenses = clone static::$_licenses;
			$class = new ReflectionClass($licenses);
			$property = $class->getProperty('licenses');
			$property->setAccessible(true);

			foreach ($property->getValue($licenses) as $id => $license) {
				$results[$id] = $license[0];
			}
			return $results;
		});
	}
}

Licenses::init();

?>