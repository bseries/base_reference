<?php
/**
 * Copyright 2017 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_reference\models;

use Composer\Spdx\SpdxLicenses;
use Exception;
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

			// Remap to our data structure, pretend as if this is a database
			// result. We might later turn this into a real entity object.
			return static::create([
				'name' => static::_prettyName($name),
				'title' => $result[0],
				'is_osi_certified' => $result[1],
				'is_deprecated' => $result[3],
				'url' => static::_prettyUrl($name, $result[2])
			]);
		});

		// Does support conditions on all fields, but does only
		// support equal operator.
		static::finder('list', function($params, $next) {
			$results = [];

			$conditions = [];
			if (isset($params['options']['conditions'])) {
				$conditions = $params['options']['conditions'];
			}
			$map = array_flip([
				0 => 'name',
				1 => 'title',
				2 => 'is_osi_certified',
				3 => 'is_deprecated'
			]);
			foreach (static::$_licenses->getLicenses() as $id => $item) {
				foreach ($conditions as $key => $value) {
					if ($item[$map[$key]] !== $value) {
						continue(2);
					}
				}
				$results[strtoupper($id)] = $item[1];
			}
			return $results;
		});
	}

	protected static function _prettyName($name) {
		if (strpos($name, 'CC') !== 0) {
			return $name;
		}
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
		return $name;
	}

	protected static function _prettyUrl($name, $url) {
		if (strpos($name, 'CC') !== 0) {
			return $url;
		}
		$base = 'https://creativecommons.org';

		if (strpos($name, 'CC0') === 0) {
			return "{$base}/publicdomain/zero/1.0/legalcode";
		}
		$type = strtolower(substr($name, 3, -4));
		$version = substr($name, -3);

		return "${base}/licenses/{$type}/{$version}/legalcode";
	}
}

Licenses::init();

?>