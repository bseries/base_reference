<?php
/**
 * Copyright 2017 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_reference\models;

use base_reference\models\Licenses;

class References extends \base_core\models\Base {

	protected $_actsAs = [
		'base_core\extensions\data\behavior\Timestamp',
		'base_core\extensions\data\behavior\Searchable' => [
			'fields' => [
				'name',
				'authors'
			]
		],
		'base_core\extensions\data\behavior\Serializable' => [
			'fields' => [
				'authors' => ','
			]
		],
	];

	public function license($entity) {
		return Licenses::find('first', [
			'conditions' => ['name' => $entity->license]
		]);
	}
}

?>