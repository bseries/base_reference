<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'base_reference', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('tags')
	]
]);

?>
<article
	class="use-rich-index"
	data-endpoint="<?= $this->url([
		'action' => 'index',
		'page' => '__PAGE__',
		'orderField' => '__ORDER_FIELD__',
		'orderDirection' => '__ORDER_DIRECTION__',
		'filter' => '__FILTER__'
	]) ?>"
>

	<div class="top-actions">
		<?= $this->html->link($t('reference'), ['action' => 'add', 'library' => 'base_reference'], [
			'class' => 'button add'
		]) ?>
	</div>

	<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="name" class="name emphasize table-sort asc"><?= $t('Name') ?>
					<td data-sort="authors" class="title table-sort"><?= $t('Author/s') ?>
					<td data-sort="title" class="title table-sort"><?= $t('Title') ?>
					<td data-sort="modified" class="date modified table-sort"><?= $t('Modified') ?>
					<td class="actions">
						<?= $this->form->field('search', [
							'type' => 'search',
							'label' => false,
							'placeholder' => $t('Filter'),
							'class' => 'table-search',
							'value' => $this->_request->filter
						]) ?>
			</thead>
			<tbody>
				<?php foreach ($data as $item): ?>
				<tr>
					<td class="name emphasize"><?= $item->name ?>
					<td class="authors"><?= $item->authors(['serialized' => true]) ?>
					<td class="title"><?= $item->title ?: '–' ?>
					<td class="date modified">
						<time datetime="<?= $this->date->format($item->modified, 'w3c') ?>">
							<?= $this->date->format($item->modified, 'date') ?>
						</time>
					<td class="actions">
						<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'base_reference'], ['class' => 'button']) ?>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>

	<?=$this->_render('element', 'paging', compact('paginator'), ['library' => 'base_core']) ?>
</article>