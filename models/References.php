<?php
/**
 * Copyright 2017 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_reference\models;

use base_reference\models\Licenses;
use lithium\g11n\Message;

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
				'authors' => ',',
				'changes' => ','
			]
		],
	];

	public static function init() {
		extract(Message::aliases());

		$model = static::_object();

		$model->validates['name'] = [
			'notEmpty' => [
				'notEmpty',
				'required' => true,
				'message' => $t('This field cannot be empty.', ['scope' => 'base_reference'])
			],
		];
	}

	public function license($entity) {
		$result = Licenses::find('first', [
			'conditions' => ['name' => $entity->license]
		]);
		if ($result) {
			return $result;
		}
		return Licenses::create([
			'name' => $entity->license,
			'title' => $entity->license
		]);
	}
}

References::init();

?>