<?php
/**
 * Copyright 2017 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_reference\config;

use lithium\g11n\Message;
use base_core\extensions\cms\Panes;

extract(Message::aliases());

Panes::register('base.references', [
	'title' => $t('References', ['scope' => 'base_reference']),
	'url' => [
		'library' => 'base_reference', 'admin' => true,
		'controller' => 'references', 'action' => 'index'
	],
	'weight' => 80
]);

?>