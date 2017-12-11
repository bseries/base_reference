<?php
/**
 * Copyright 2017 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace app\extensions\helper;

use lithium\data\Entity;

class References extends \lithium\template\Helper {

	// Holds a list of references that have been used when citing.
	protected $_cited = [];

	// Generates a HTML fragmet for insertion next to the text or media.
	//
	// The reference style can be changed via the `style` option. It can either be `long`,
	// `medium` or `short`. Remembers rendered quotes so we can output a numbered list
	// using `quoted()` later.
	//
	// short-style:
	// `<number>`
	//
	// medium-style:
	// <currently unused>
	//
	// long-style:
	// `<number> <name>, <source>, <short license>
	public function cite(Entity $entity, array $options = []) {
		$options += ['style' => 'short'];

		$this->_cited[] = $entity;
		$number = count($this->_cited);

		if ($options['style'] === 'short') {
			return sprintf('<div id="citation-%d" class="ref">%s</div>',
				$number,
				$this->_context->html->link($number, "#ref-{$number}", ['class' => 'ref__number'])
			);
		}
		return sprintf('<div id="citation-%d" class="ref">%s %s, %s, %s</div>',
			$number,
			$this->_context->html->link($number, "#ref-{$number}", ['class' => 'ref__number']),
			$this->_authors($entity->authors(['serialized' => true])),
			$this->_source($entity->source),
			$this->_license($entity->license(), 'short')
		);
	}

	// Renders a list of cited referenes.
	public function index() {
		$html = '<ol class="refs-index">' . "\n";
		foreach ($this->_cited as $key => $entity) {
			$html .= $this->_item($key, $entity) . "\n";
		}
		$html .= '</ol>' . "\n";

		return $html;
	}

	// Renders a HTML fragment for the quoted entity along with its index, to be used inside
	// a reference list section.
	//
	// style:
	// `<back> <number> <name>, <source>, <long license>
	protected function _item($key, Entity $entity) {
		$number = $key + 1;

		return sprintf('<div id="ref-%d" class="ref">%s %s %s, %s, %s</div>',
			$number,
			$this->_context->html->link('hochspringen', "#citation-{$number}", ['class' => 'ref__back']),
			$this->_number($number),
			$this->_authors($entity->authors(['serialized' => true])),
			$this->_source($entity->source),
			$this->_license($entity->license(), 'long')
		);
	}

	protected function _number($key) {
		return sprintf('<span class="ref__number">%d</span>', $key + 1);
	}

	protected function _authors($names) {
		return sprintf('<span class="ref__authors">%s</span>', $names);
	}

	// Either takes a destrciption of the source or an URL and outputs an anchor element
	// for it. When possible, will use a nicely formatted title instead of the URL itself
	// for known locations.
	protected function _source($source) {
		if (strpos($source, '://') === false) {
			return $source;
		}
		// Map of regular expressions to titles.
		$known = [
			'#^https://commons.wikimedia.org#' => 'Wikimedia Commons'
		];
		foreach ($known as $match => $title) {
			if (preg_match($match, $source)) {
				return $this->_context->html->link($title, $source, [
					'class' => 'ref__source'
				]);
			}
		}
		return $this->_context->html->link($source, [
			'class' => 'ref__source'
		]);
	}

	protected function _license(array $license, $style = 'long') {
		return $this->_context->html->link(
			$style === 'long' ? $license['title'] : $license['name'],
			$license['url'],
			['class' => 'ref__license']
		);
	}
}

?>