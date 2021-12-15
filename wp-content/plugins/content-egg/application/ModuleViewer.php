<?php

namespace ContentEgg\application;

use ContentEgg\application\components\ModuleManager;
use ContentEgg\application\components\ContentManager;
use ContentEgg\application\components\ModuleTemplateManager;
use ContentEgg\application\components\Shortcoded;
use ContentEgg\application\helpers\ArrayHelper;

/**
 * ModuleViewer class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
class ModuleViewer {

    private static $instance = null;
    private $module_data_pointer = array();

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self;

        return self::$instance;
    }

    private function __construct()
    {
        
    }

    public function init()
    {
        // priority = 12 because do_shortcode() is registered as a default filter on 'the_content' with a priority of 11. 
        \add_filter('the_content', array($this, 'viewData'), 12);
    }

    public function viewData($content)
    {
        global $post;
        /*
          if (!is_single() && !is_page)
          return $content;
         * 
         */
        $top_modules_priorities = array();
        $bottom_modules_priorities = array();
        foreach (ModuleManager::getInstance()->getModules(true) as $module_id => $module)
        {
            $embed_at = $module->config('embed_at');
            if ($embed_at != 'post_bottom' && $embed_at != 'post_top')
                continue;
            if (Shortcoded::getInstance($post->ID)->isShortcoded($module->getId()))
                continue;

            $priority = (int) $module->config('priority');
            if ($embed_at == 'post_top')
                $top_modules_priorities[$module_id] = $priority;
            elseif ($embed_at == 'post_bottom')
                $bottom_modules_priorities[$module_id] = $priority;
        }

        // sort by priority, keep module_id order
        $top_modules_priorities = ArrayHelper::asortStable($top_modules_priorities);
        $bottom_modules_priorities = ArrayHelper::asortStable($bottom_modules_priorities);
        
        // reverse for corret gluing order
        $top_modules_priorities = array_reverse($top_modules_priorities, true);
        foreach ($top_modules_priorities as $module_id => $p)
        {
            $content = $this->viewModuleData($module_id) . $content;
        }
        foreach ($bottom_modules_priorities as $module_id => $p)
        {
            $content = $content . $this->viewModuleData($module_id);
        }

        return $content;
    }

    public function viewModuleData($module_id, $post_id = null, $params = array())
    {
        if (!$post_id)
        {
            global $post;
            $post_id = $post->ID;
        }
        $module = ModuleManager::factory($module_id);
        $data = \get_post_meta($post_id, ContentManager::META_PREFIX_DATA . $module->getId(), true);
        if (!$data)
            return '';
			
		$keyword = \get_post_meta($post_id, ContentManager::META_PREFIX_KEYWORD . $module->getId(), true);			
        
        // locale fix...
        if (!empty($params['locale']))
        {
            foreach ($data as $key => $d)
            {
                if(isset($d['extra']['locale']) && strtolower($d['extra']['locale']) != strtolower($params['locale']))
                    unset($data[$key]);
            }
        }        

        if (!isset($this->module_data_pointer[$post_id]))
            $this->module_data_pointer[$post_id] = array();
        // next param
        if (!empty($params['next']))
        {
            if (!isset($this->module_data_pointer[$post_id][$module_id]))
                $this->module_data_pointer[$post_id][$module_id] = 0;

            $data = array_splice($data, $this->module_data_pointer[$post_id][$module_id], $params['next']);
            if (count($data) < $params['next'])
                $params['next'] = count($data);

            $this->module_data_pointer[$post_id][$module_id] += $params['next'];
        } elseif (!empty($params['limit']))
        {
            if (!isset($params['offset']))
                $params['offset'] = 0;

            $data = array_splice($data, $params['offset'], $params['limit']);
            $this->module_data_pointer[$post_id][$module_id] = $params['offset'] + $params['limit'];
        }
        if (!$data)
            return;

        // template
        $tpl_manager = ModuleTemplateManager::getInstance($module->getId());
        if (!empty($params['template']) && $tpl_manager->isTemplateExists($params['template']))
            $template = $params['template'];
        else
            $template = $module->config('template');

        return $tpl_manager->render($template, array('items' => $data, 'title' => $module->config('tpl_title'), 'keyword' => $keyword));
    }

}
