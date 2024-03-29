<?php
/**
 *
 * @copyright   Copyright (C) J�r�mie Fays. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<dl class="search-results<?php echo $this->pageclass_sfx; ?>">
<?php foreach ($this->results as $result) : ?>
	<dt class="result-title">
		<?php //echo $this->pagination->limitstart + $result->count.'. ';?>
		<?php /*if (1) :?>
			<a href="<?php echo JRoute::_($result->href); ?>">
				<?php echo $this->escape($result->title);?>
			</a>
		<?php else:?>
			<?php echo $this->escape($result->title);?>
		<?php endif; */?>
	</dt>
	<dd class="result-text">
		<?php echo $result->introtext; ?>
	</dd>
	<?php /* if ($this->params->get('show_date')) : ?>
		<dd class="result-created<?php echo $this->pageclass_sfx; ?>">
			<?php echo JText::sprintf('JGLOBAL_CREATED_DATE_ON', $result->created); ?>
		</dd>
	<?php endif; */ ?>
<?php endforeach; ?>
</dl>

<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<p class="counter">
	<?php echo $this->pagination->getPagesCounter(); ?>
</p>