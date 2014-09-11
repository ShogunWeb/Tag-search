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
 * TagSearch Component Controller
 */
class TagSearchController extends JControllerLegacy
{
    /**
     * Method to display a view.
     *
     * @param   boolean			If true, the view output will be cached
     * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController		This object to support chaining.
     * @since   ---
     */
    public function display($cachable = false, $urlparams = false)
    {
        $this->input->set('view', 'tagsearch'); // force it to be the tagsearch view
        $app	= JFactory::getApplication();

        $session =& JFactory::getSession();
        $post = $session->get('TagSearchPost'); // gets data stored in session
        
        
        if($post){  //puts data from session into Jinput
            foreach($post AS $key=>$value)
            {
                $this->input->set($key, $value);
            }
        }
        
//        $app->enqueueMessage('Controller display', 'error');
        return parent::display($cachable, $urlparams);
    }

    public function search()
    {
        $app	= JFactory::getApplication();
         
        $post['id'] = $this->input->getInt('id');
        
        $searchTags = $this->input->get('searchTags', array(), 'ARRAY');
        $post['searchTags'] = $searchTags;
        
        
        $tagSearchOption =  $this->input->getString('tagSearchOption', null, 'post');
        $post['tagSearchOption'] = $tagSearchOption;
        
 
        $post['ordering']     = $this->input->getWord('ordering', null, 'post');
//        $post['searchphrase'] = $this->input->getWord('searchphrase', 'all', 'post');
        $post['limit']        = $this->input->getUInt('limit', null, 'post');

        if ($post['limit'] === null)
        {
                unset($post['limit']);
        }

        // set Itemid id for links from menu
        $app	= JFactory::getApplication();
        $menu	= $app->getMenu();
        $items	= $menu->getItems('link', 'index.php?option=com_search&view=search');
        //$app->enqueueMessage('Task = search', 'error');

        if (isset($items[0]))
        {
                $post['Itemid'] = $items[0]->id;
        } elseif ($this->input->getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
                $post['Itemid'] = $this->input->getInt('Itemid');
        }

        unset($post['task']);
        unset($post['submit']);

        $uri = JUri::getInstance();
        $session =& JFactory::getSession();
        $session->set('TagSearchPost', $post);
        //$uri->setQuery($post);
        $uri->setVar('option', 'com_tagsearch');

        $this->setRedirect(JRoute::_('index.php'.$uri->toString(array('query', 'fragment')), false));
    }
}
