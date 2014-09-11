<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
require_once(JPATH_SITE.'/components/com_tags/helpers/route.php');

 
/**
 * TagSearch class for the TagSearch Component
 */
class TagSearchViewTagSearch extends JViewLegacy
{
    // Overwriting JView display method
    function display($tpl = null)
    {
        $app     = JFactory::getApplication();
        $uri     = JUri::getInstance();

        $error   = null;
        $rows    = null;
        $results = null;
        $total   = 0;

        // Get some data from the POST
/*        $searchTags = $this->input->getString('searchTags', null, 'post');
        $tagSearchOption =  $this->input->getString('tagSearchOption', null, 'post');
  */      
        // Get some data from the model
        $state      = $this->get('state');
        $params     = $app->getParams();

        $menus = $app->getMenu();
        $menu  = $menus->getActive();

        // Because the application sets a default page title, we need to get it right from the menu item itself
        if (is_object($menu))
        {
                $menu_params = new JRegistry;
                $menu_params->loadString($menu->params);

                if (!$menu_params->get('page_title'))
                {
                        $params->set('page_title', JText::_('COM_TAGSEARCH_TAGSEARCH'));
                }
        }
        else
        {
                $params->set('page_title', JText::_('COM_TAGSEARCH_TAGSEARCH'));
        }

        $title = $params->get('page_title');

        if ($app->getCfg('sitename_pagetitles', 0) == 1)
        {
                $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
        {
                $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }

        $this->document->setTitle($title);

        if ($params->get('menu-meta_description'))
        {
                $this->document->setDescription($params->get('menu-meta_description'));
        }

        if ($params->get('menu-meta_keywords'))
        {
                $this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
        }

        if ($params->get('robots'))
        {
                $this->document->setMetadata('robots', $params->get('robots'));
        }

        // Built select lists
        $tagSearchOption = $this->get('tagSearchOption');
        $checked_all="checked ";
        $active_all=" active";
        $checked_any="";
        $active_any="";
        if($tagSearchOption == "any")
        {
            $checked_all="";
            $active_all="";
            $checked_any="checked ";
            $active_any=" active";
        }
        
        $lists             = array();

        $searchoptions         = array();
        $searchoptions[]       = '<label class="btn'.$active_all.'"><input id="TS-all" type="radio" value="all" name="tagSearchOption"'.$checked_all.'>'.JText::_('COM_TAGSEARCH_ALL_TAGS').'</label>';
        $searchoptions[]       = '<label class="btn'.$active_any.'"><input id="TS-any" type="radio" value="any" name="tagSearchOption"'.$checked_any.'>'.JText::_('COM_TAGSEARCH_ANY_TAGS').'</label>';
        $lists['searchoptions'] = implode($searchoptions);
        
        $alltags = $this->get('AllTags');
        $searchTags = $this->get('searchTags');
        $tags   = array();
        $i=0;
        
        foreach($alltags as $tag){
            $checked = "";
            if( in_array($tag->id, $searchTags) ) { $checked = " checked ";} else {$checked = "";}
            $tags[] = '<div class="span3"><input type="checkbox" name="searchTags[]" id="stag'.$tag->id.'" value="'.$tag->id.'" '.$checked.'><label for="stag'.$tag->id.'">'.$tag->title.'</label></div>';
        }
        $lists['alltags'] = implode($tags);
        
        
        //Build the tag list
        $tagList = array();
        foreach($alltags as $tag){
            $tagList[] = '<h3><a href="'.JRoute::_(TagsHelperRoute::getTagRoute($tag->id)).'">'.$tag->title.'</a></h3>';
        }
        $lists['tagList'] = implode($tagList);
        
        
        //Get articles from tag search----------------------------
        $results    = $this->get('data');
        $total      = $this->get('total');
        $pagination = $this->get('pagination');

        //$app->enqueueMessage(print_r($results, true), 'error');
        
        //require_once JPATH_SITE . '/components/com_content/helpers/route.php';

        for ($i = 0, $count = count($results); $i < $count; $i++)
        {
                $result = & $results[$i];

                if ($result->created)
                {
                        $created = JHtml::_('date', $result->created, JText::_('DATE_FORMAT_LC3'));
                }
                else
                {
                        $created = '';
                }

                $result->introtext    = JHtml::_('content.prepare', $result->introtext, '', 'com_search.search');
                $result->created = $created;
                //$result->count = $i + 1;
                
                //$app->enqueueMessage($result->title.'<br />', 'warning');
        }


        // Check for layout override
        $active = JFactory::getApplication()->getMenu()->getActive();

        if (isset($active->query['layout']))
        {
                $this->setLayout($active->query['layout']);
        }

        // Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
        $this->pagination    = &$pagination;

        $this->lists         = &$lists;
        $this->params        = &$params;
/*
        $this->ordering      = $state->get('ordering');
        $this->searchword    = $searchword;
        $this->origkeyword   = $state->get('origkeyword');
        $this->searchphrase  = $state->get('match');
        $this->searchareas   = $areas;
        $this->action        = $uri;
*/
        // Assign data to the view
        $this->item = $this->get('Item');
        $this->state = &$state;    
        $this->total         = $total;
        $this->error         = NULL;
        $this->results       = &$results;
        //$this->results       = NULL;
        
        parent::display($tpl);
    }
}
