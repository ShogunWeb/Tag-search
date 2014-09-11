<?php
/**
 * @copyright	Copyright (C) 2013 JŽrŽmie Fays
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of TagSearch component
 */
class TagSearchController extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false)
	{	// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'TagSearchs'));
 
		// call parent behavior
		parent::display($cachable);
 
		// Set the submenu
		TagSearchHelper::addSubmenu('messages');
	}
}
