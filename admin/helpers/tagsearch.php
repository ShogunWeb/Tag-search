<?php
/**
 * @copyright	Copyright (C) 2013 JŽrŽmie Fays
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * TagSearch component helper.
 */
abstract class TagSearchHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_TAGSEARCH_SUBMENU_MESSAGES'), 'index.php?option=com_tagsearch', $submenu == 'messages');
		JSubMenuHelper::addEntry(JText::_('COM_TAGSEARCH_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_tagsearch', $submenu == 'categories');
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-tagsearch {background-image: url(../media/com_tagsearch/images/tux-48x48.png);}');
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_TAGSEARCH_ADMINISTRATION_CATEGORIES'));
		}
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
 
		if (empty($messageId)) {
			$assetName = 'com_tagsearch';
		}
		else {
			$assetName = 'com_tagsearch.message.'.(int) $messageId;
		}
 
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);
 
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
 
		return $result;
	}
}
