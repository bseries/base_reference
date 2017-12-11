<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'base_reference', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'single',
		'title' => $item->name,
		'empty' => $t('unnamed'),
		'object' => $t('reference')
	],
	'meta' => []
]);

?>
<article>
	<?=$this->form->create($item) ?>
		<?php if ($item->exists()): ?>
			<?= $this->form->field('id', ['type' => 'hidden']) ?>
		<?php endif ?>
		<div class="grid-row">
			<div class="grid-column-left">
				<?= $this->form->field('name', [
					'type' => 'text',
					'label' => $t('Name'),
					'class' => 'use-for-title'
				]) ?>
				<div class="help"><?= $t('i.e. example2018') ?></div>
			</div>
		</div>

		<div class="grid-row">
			<div class="grid-column-left">
				<?= $this->form->field('title', [
					'type' => 'text',
					'label' => $t('Title'),
					'class' => 'use-for-title'
				]) ?>
			</div>
			<div class="grid-column-right">
				<?= $this->form->field('authors', [
					'type' => 'text',
					'label' => $t('Author/s'),
					'value' => $item->authors(['serialized' => true]) ?: $authedUser->name
				]) ?>
				<div class="help"><?= $t('The original author/s. Separate multiple authors with commas.') ?></div>

				<?= $this->form->field('changes', [
					'type' => 'text',
					'label' => $t('Changes'),
					'value' => $item->changes(['serialized' => true])
				]) ?>
				<div class="help">
					<?= $t('Authors of modifications, i.e. Color correction by Atelier Disko') ?>
					<?= $t('Separate multiple changes with commas.') ?>
				</div>
			</div>
		</div>
		<div class="grid-row">
			<div class="grid-column-left">
				<?= $this->form->field('source', [
					'type' => 'text',
					'label' => $t('Source')
				]) ?>
				<div class="help"><?= $t('An URL or a description of the source.') ?></div>
			</div>
			<div class="grid-column-right">
				<?= $this->form->field('license', [
					'type' => 'text',
					'label' => $t('License')
				]) ?>
				<div class="help">
					<?php echo $t('A {:SPDX_license_identifier} or a freeform license string.', [
						'SPDX_license_identifier' => $this->html->link(
							$t('SPDX license identifier'),
							'https://spdx.org/licenses/',
							['target' => 'new']
						)
					]) ?>
				</div>
			</div>
		</div>

		<div class="bottom-actions">
			<div class="bottom-actions__left">
				<?php if ($item->exists()): ?>
					<?= $this->html->link($t('delete'), [
						'action' => 'delete', 'id' => $item->id
					], ['class' => 'button large delete']) ?>
				<?php endif ?>
			</div>
			<div class="bottom-actions__right">
				<?= $this->form->button($t('save'), [
					'type' => 'submit',
					'class' => 'button large save'
				]) ?>
			</div>
		</div>

	<?=$this->form->end() ?>
</article>