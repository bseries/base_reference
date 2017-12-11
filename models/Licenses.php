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
				'name' => $name,
				'title' => $result[0],
				'is_osi_certified' => $result[1],
				'url' => $result[2]
			]);
		});
	}
}

Licenses::init();

?>