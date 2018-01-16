<?php
/**
 * Copyright 2017 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_reference\controllers;

use base_reference\models\Licenses;

class ReferencesController extends \base_core\controllers\BaseController {

	use \base_core\controllers\AdminIndexTrait;
	use \base_core\controllers\AdminAddTrait;
	use \base_core\controllers\AdminEditTrait;
	use \base_core\controllers\AdminDeleteTrait;

	protected function _selects($item = null) {
		$licenseSuggestions = array_filter(array_keys(Licenses::find('list')), function($v) {
			$item = Licenses::find('first', ['conditions' => ['name' => $v]]);
			return !$item->is_deprecated;
		});
		return compact('licenseSuggestions');
	}
}

?>