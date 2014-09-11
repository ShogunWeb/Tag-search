<?php
/**
 * @copyright	Copyright (C) 2013 Jrmie Fays
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_tagsearch')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}
 
// require helper file
JLoader::register('TagSearchHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'tagsearch.php');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by TagSearch
$controller = JControllerLegacy::getInstance('TagSearch');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
