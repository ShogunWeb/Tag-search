<?php
/**
 * @copyright   Copyright (C) JŽrŽmie Fays. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
?>
<form id="tagSearchForm" action="<?php echo JRoute::_('index.php?option=com_tagsearch');?>" method="post">
	
	<h3 class="tagsearch_title">Search by service</h3>
	<p>Click a service to see the list of all companies providing it</p>
	<div class="tag-category tagsearch">
		<?php echo $this->lists['tagList']; ?>
	</div>
	<div class="clearfix"></div>

	<h3 class="tagsearch_title">Search by service</h3>
	<p>Check the services needed and choose the "all" or "any" option, click <em>Search</em> to see results</p>
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
	<div class="check-tags row">
		<?php echo $this->lists['alltags']; ?>
	</div>
	
	<div id="TS" class="radio btn-group" data-toggle="buttons-radio">
			<?php echo $this->lists['searchoptions']; ?>
	</div>		
	
	<div class="btn-toolbar">
		<div class="btn-group pull-left">
			<button name="Search" onclick="this.form.submit()" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('COM_TAGSEARCH_TAGSEARCH');?>"><?php echo JText::_('COM_TAGSEARCH_TAGSEARCH_TXT')." "; ?><span class="icon-search"></span></button>
		</div>
		<input type="hidden" name="task" value="search" />
		<div class="clearfix"></div>
	</div>

	<?php if ($this->total > 0) : ?>
		<div class="form-limit">
			<label for="limit">
				<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
			</label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	<?php endif; ?>

</form>
