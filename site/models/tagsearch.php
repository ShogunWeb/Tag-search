<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

 
/**
 * TagSearch Model
 */
class TagSearchModelTagSearch extends JModelItem
{
	/**
	 * Search data array
	 *
	 * @var array
	 */
	protected $_data = null;

	/**
	 * Search total
	 *
	 * @var integer
	 */
	protected $_total = null;

	/**
	 * Search areas
	 *
	 * @var integer
	 */
	protected  $_areas = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	protected $_pagination = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	public function __construct()
	{
		parent::__construct();

		//Get configuration
		$app    = JFactory::getApplication();
		$config = JFactory::getConfig();

		// Get the pagination request variables
		$this->setState('limit', $app->getUserStateFromRequest('com_search.limit', 'limit', $config->get('list_limit'), 'uint'));
		$this->setState('limitstart', $app->input->get('limitstart', 0, 'uint'));
		

		// Set the search parameters
//		$keyword  = urldecode($app->input->getString('searchword'));
//		$match    = $app->input->get('searchphrase', 'all', 'word');
		$ordering = $app->input->get('ordering', 'newest', 'word');
		$this->setState('ordering', $ordering);
		
		$searchTags = $app->input->get('searchTags', array(), 'ARRAY');
		$this->setState('searchTags', $searchTags);
		
		$tagSearchOption =  $app->input->getString('tagSearchOption', null, 'post');
		$this->setState('tagSearchOption', $tagSearchOption);


//		$this->setSearch($keyword, $match, $ordering);

		//Set the search areas
/*		$areas = $app->input->get('areas', null, 'array');
		$this->setAreas($areas);
*/
	}

	/**
	 * Method to set the search parameters
	 *
	 * @access	public
	 * @param string search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 */
	public function setSearch($keyword, $match = 'all', $ordering = 'newest')
	{
		if (isset($keyword))
		{
			$this->setState('origkeyword', $keyword);
			if ($match !== 'exact')
			{
				$keyword = preg_replace('#\xE3\x80\x80#s', ' ', $keyword);
			}
			$this->setState('keyword', $keyword);
		}

		if (isset($match))
		{
			$this->setState('match', $match);
		}

		if (isset($ordering))
		{
			$this->setState('ordering', $ordering);
		}
	}

	/**
	 * Method to set the search areas
	 *
	 * @access	public
	 * @param   array  Active areas
	 * @param   array  Search areas
	 */
	public function setAreas($active = array(), $search = array())
	{
		$this->_areas['active'] = $active;
		$this->_areas['search'] = $search;
	}

	/**
	 * Method to get weblink item data for the category
	 *
	 * @access public
	 * @return array
	 */
	public function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
/*			$areas = $this->getAreas();

			JPluginHelper::importPlugin('search');
			$dispatcher = JEventDispatcher::getInstance();
			$results = $dispatcher->trigger('onContentSearch', array(
				$this->getState('keyword'),
				$this->getState('match'),
				$this->getState('ordering'),
				$areas['active'])
			);

			$rows = array();
			foreach ($results as $result)
			{
				$rows = array_merge((array) $rows, (array) $result);
			}

			$this->_total	= count($rows);
			if ($this->getState('limit') > 0)
			{
				$this->_data	= array_splice($rows, $this->getState('limitstart'), $this->getState('limit'));
			} else {
				$this->_data = $rows;
			}
*/
			//$limit = 10;
			
			$this->_data = array();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
		
			
			if($tags = $this->getState('searchTags'))
			{
				if($this->getState('tagSearchOption')=='all') {
					$query->from('#__contentitem_tag_map AS m');
					$query->select('DISTINCT a.id AS id, a.catid AS catid, a.title AS title, a.introtext AS introtext, a.created AS created, "" AS count, "" AS href, GROUP_CONCAT(m.tag_id) AS tag_ids');
					$query->join('inner', '#__content as a ON m.content_item_id = a.id');
					$query->group('a.id');
					$tag_query = array();
					if(count($tags)>0)
					{
						foreach($tags as $tag){
							$tag_query[] = 'tag_ids LIKE "%'.$tag.'%"';
						}
						$having = implode (' AND ', $tag_query);
						$query->having($having);
						$where= '';
					}
					else {$where = " AND (FALSE)";}
				}
				else {
					$query->from('#__contentitem_tag_map AS m');
					$query->select('DISTINCT a.id AS id, a.catid AS catid, a.title AS title, a.introtext AS introtext, a.created AS created, "" AS count, "" AS href');
					$query->join('inner', '#__content as a ON m.content_item_id = a.id');
					if(count($tags)>0) {$where = ' AND (m.tag_id IN ('.implode(', ', $tags).'))';}
					else {$where = ' AND (1)';}
				}
				
				$query->where('m.type_alias="com_content.article" AND a.state = 1 AND catid='.$this->getState('category.id').$where.'');
				
				//sets the final query before asking the database
				$db->setQuery($query);
				//loads results
				if (!$rows = $db->loadObjectList()) 
				{
					$this->setError($this->_db->getError());
				}
				$this->_total	= count($rows);
				
				require_once JPATH_SITE . '/components/com_content/helpers/route.php';
				//give incremental number to each entry in the table, to be able to paginate
				for ($i = 0, $count = count($rows); $i < $count; $i++)
				{
					$rows[$i]->count = $i + 1;
					$rows[$i]->href = ContentHelperRoute::getArticleRoute($rows[$i]->id, $rows[$i]->catid);
				}
				
				// calculate pagination (restrict output to page)
				if ($this->getState('limit') > 0)
				{
					$this->_data	= array_splice($rows, $this->getState('limitstart'), $this->getState('limit'));
				} else {
					$this->_data = $rows;
				}				
			}
			
			
		}
		return $this->_data;
	}

	/**
	 * Method to get the total number of weblink items for the category
	 *
	 * @access public
	 * @return  integer
	 */
	public function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Method to get a pagination object of the weblink items for the category
	 *
	 * @access public
	 * @return  integer
	 */
	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	/**
	 * Method to get the list of selected tags
	 *
	 * @access public
	 * @return  array
	 */
	public function getSearchTags()
	{
		return $this->getState('searchTags');
	}

        
	/**
	 * Method to get the state of the ALL/ANY option
	 *
	 * @access public
	 * @return  array
	 */
	public function getTagSearchOption()
	{
		return $this->getState('tagSearchOption');
	}
        
        
        
        



	
	/**
	 * @var object item
	 */
	protected $item;

	/**
	 * @var object item
	 */
	protected $allTags;
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState() 
	{
		$app = JFactory::getApplication();

		// Get the category id
		$id = JRequest::getInt('id');		
		$this->setState('category.id', $id);
		//$message = "category ID : ".$id."<br />";
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		
		//$app->enqueueMessage('Populate State: '.$message, 'error');
		
		parent::populateState();
	}
 
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'TagSearch', $prefix = 'TagSearchTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Load an JSON string into the registry into the given namespace [or default if a namespace is not given]
	 *
	 * @param    string    JSON formatted string to load into the registry
	 * @return    boolean True on success
	 * @since    1.5
	 * @deprecated 1.6 - Oct 25, 2010
	 */
	public function loadJSON($data)
	{
	    return $this->loadString($data, 'JSON');
	}
 
	/**
	 * Get the filtered Category
	 * @return object : The article category in which tag search has to be done
	 */
	public function getItem() 
	{

		if (!isset($this->item)) 
		{
			$id = $this->getState('category.id');
			$this->_db->setQuery($this->_db->getQuery(true)
				->from('#__categories as c')
				->select('id, title as category')
				->where('c.id=' . (int)$id));
			if (!$this->item = $this->_db->loadObject()) 
			{
				$this->setError($this->_db->getError());
			}

		}
		return $this->item;
	}
	
	
	/**
	 * Returns an array with all published tags.
	 *
	 * @return object : array with all published tags
	 * @since	1.6
	 */
	public function getAllTags() 
	{
		if (!isset($this->allTags)) 
		{
			$this->_db->setQuery($this->_db->getQuery(true)
				->from('#__tags as t')
				->select('id, title')
				->where('(t.level > 0) AND (published = 1)'));
			if (!$this->allTags = $this->_db->loadObjectList()) 
			{
				$this->setError($this->_db->getError());
			}

		}		
		return $this->allTags;
	}		
	
}
