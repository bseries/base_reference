<?php
/**
 * Copyright 2017 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_reference\extensions\helper;

use lithium\data\Entity;
use lithium\g11n\Message;

class References extends \lithium\template\Helper {

	// Holds a list of references that have been used when citing.
	protected $_cited = [];

	// Generates a HTML fragmet for insertion next to the text or media.
	//
	// The reference style can be changed via the `style` option. It can either be `long`,
	// `medium` or `short`. Remembers rendered citation so we can output a numbered list
	// using `index()` later.
	//
	// short-style:
	// `<number>`
	//
	// medium-style:
	// <currently unused>
	//
	// long-style:
	// `<number> <authors>, <title, linked with source>, <changes>, <short license, linked>`
	public function cite(Entity $entity, array $options = []) {
		extract(Message::aliases());

		$options += ['style' => 'short', 'class' => null];

		$this->_cited[] = $entity;
		$number = count($this->_cited);

		$class = 'ref';
		if ($options['class']) {
			$class .= " {$options['class']}";
		}

		if ($options['style'] === 'short') {
			return sprintf('<div id="citation-%d" class="%s" aria-label="%s">%s</div>',
				$number,
				$class,
				$t('citation number {:number}', ['number' => $number, 'scope' => 'base_reference']),
				$this->_context->html->link($number, "#ref-{$number}", [
					'class' => 'ref__number',
					'aria-label' => $t('jump to reference in index', ['scope' => 'base_reference'])
				])
			);
		}
		$parts = [];

		if ($entity->authors) {
			$parts[] = $this->_authors($entity->authors(['serialized' => true]));
		}
		if ($entity->title) {
			$parts[] = $this->_title($entity->title, $entity->source);
		}
		if ($entity->changes) {
			$parts[] = $this->_changes($entity->changes(['serialized' => true]));
		}
		if ($entity->license) {
			$parts[] = $this->_license($entity->license(), 'short');
		}
		return sprintf('<div id="citation-%d" class="%s" aria-label="%s">%s %s</div>',
			$number,
			$class,
			$t('citation number {:number}', ['number' => $number, 'scope' => 'base_reference']),
			$this->_context->html->link($number, "#ref-{$number}", [
				'class' => 'ref__number',
				'aria-label' => $t('jump to reference in index', ['scope' => 'base_reference'])
			]),
			implode(', ', $parts)
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
	// `<back> <number> <authors>, <title, linked with source>, <changes>, <long license, linked>`
	protected function _item($key, Entity $entity) {
		extract(Message::aliases());

		$number = $key + 1;

		$parts = [];

		if ($entity->authors) {
			$parts[] = $this->_authors($entity->authors(['serialized' => true]));
		}
		if ($entity->title) {
			$parts[] = $this->_title($entity->title, $entity->source);
		}
		if ($entity->changes) {
			$parts[] = $this->_changes($entity->changes(['serialized' => true]));
		}
		if ($entity->license) {
			$parts[] = $this->_license($entity->license(), 'short');
		}
		return sprintf('<div id="ref-%d" class="ref" aria-label="%s">%s %s %s</div>',
			$number,
			$t('reference number {:number}', ['number' => $number, 'scope' => 'base_reference']),
			$this->_context->html->link(
				$t('jump back to citation', ['scope' => 'base_reference']),
				"#citation-{$number}",
				['class' => 'ref__back']),
			$this->_number($key),
			implode(', ', $parts)
		);
	}

	protected function _number($key) {
		extract(Message::aliases());

		return sprintf('<span class="ref__number" aria-label="%s">%d</span>',
			$t('reference number {:number}', ['number' => $key + 1, 'scope' => 'base_reference']),
			$key + 1
		);
	}

	protected function _authors($names) {
		return sprintf('<span class="ref__authors">%s</span>', $names);
	}

	protected function _changes($changes) {
		return sprintf('<span class="ref__changes">%s</span>', $changes);
	}

	// Returns a linked to source title, when source is an URL.
	protected function _title($title, $source) {
		if (strpos($source, '://') === false) {
			return sprintf('<span class="ref__title">%s</span>', $title);
		}
		return $this->_context->html->link($title, $source, [
			'class' => 'ref__title'
		]);
	}

	protected function _license(Entity $license, $style = 'long') {
		if (!$license->url) {
			return sprintf(
				'<span class="ref__license">%s</span>',
				$style === 'long' ? $license->title : $license->name
			);
		}
		return $this->_context->html->link(
			$style === 'long' ? $license->title : $license->name,
			$license->url,
			['class' => 'ref__license']
		);
	}
}

?>