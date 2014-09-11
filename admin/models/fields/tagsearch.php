<?php
/**
 * @copyright	Copyright (C) 2013 JŽrŽmie Fays
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * TagSearch Form Field class for the TagSearch component
 */
class JFormFieldTagSearch extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'TagSearch';
 
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
		$db = JFactory::getDBO();
//		$query = new JDatabaseQuery;
		$query = $db->getQuery(true);
		$query->select('id, title');
		$query->from('#__categories');
		$query->where('extension = '.$db->quote('com_content').'');
		$db->setQuery((string)$query);
		$messages = $db->loadObjectList();
		$options = array();
		if ($messages)
		{
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->id, $message->title );
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
