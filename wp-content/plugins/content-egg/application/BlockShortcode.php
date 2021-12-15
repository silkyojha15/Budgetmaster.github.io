<?php

namespace ContentEgg\application;

use ContentEgg\application\components\ModuleManager;
use ContentEgg\application\components\BlockTemplateManager;
use ContentEgg\application\components\Shortcoded;
use ContentEgg\application\components\ContentManager;

/**
 * BlockShortcode class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
class BlockShortcode {

    const shortcode = 'content-egg-block';

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self;
        return self::$instance;
    }

    private function __construct()
    {
        \add_shortcode(self::shortcode, array($this, 'viewData'));
    }

    private function prepareAttr($atts)
    {
        $a = shortcode_atts(array(
            'modules' => null,
            'template' => '',
                ), $atts);

        if ($a['modules'])
        {
            $modules = explode(',', $a['modules']);
            $module_ids = array();
            foreach ($modules as $key => $module_id)
            {
                $module_id = trim($module_id);
                if (ModuleManager::getInstance()->isModuleActive($module_id))
                    $module_ids[] = $module_id;
            }
            $a['modules'] = $module_ids;
        } else
            $a['modules'] = array();

        if ($a['template'])
        {
            $a['template'] = BlockTemplateManager::getInstance()->prepareShortcodeTempate($a['template']);
        }

        return $a;
    }

    public function viewData($atts, $content = "")
    {
        global $post;

        $a = $this->prepareAttr($atts);
        $tpl_manager = BlockTemplateManager::getInstance();
        if (empty($a['template']) || !$tpl_manager->isTemplateExists($a['template']))
            return;
        else
        if (!$template_file = $tpl_manager->getViewPath($a['template']))
            return '';

        // Get supported modules for this tpl
        $headers = \get_file_data($template_file, array('module_ids' => 'Modules', 'module_types' => 'Module Types'));
        $supported_module_ids = array();
        if ($headers && !empty($headers['module_ids']))
        {
            $supported_module_ids = explode(',', $headers['module_ids']);
            $supported_module_ids = array_map('trim', $supported_module_ids);
        } elseif ($headers && !empty($headers['module_types']))
        {
            $module_types = explode(',', $headers['module_types']);
            $module_types = array_map('trim', $module_types);
            $supported_module_ids = ModuleManager::getInstance()->getParserModuleIdsByTypes($module_types, true);
        }

        // Module IDs from shortcode param. Validated.
        if ($a['modules'])
            $module_ids = $a['modules'];
        else
            $module_ids = ModuleManager::getInstance()->getParserModulesIdList(true);

        // Пересечение
        if ($supported_module_ids)
        {
            $module_ids = array_intersect($module_ids, $supported_module_ids);
        }

        // Get modules data
        $data = array();
        foreach ($module_ids as $module_id)
        {
            $module_data = \get_post_meta($post->ID, ContentManager::META_PREFIX_DATA . $module_id, true);
            if ($module_data)
                $data[$module_id] = $module_data;

            // shortcoded!
            Shortcoded::getInstance($post->ID)->setShortcodedModule($module_id);
        }
        if (!$data)
            return;
        
        return $tpl_manager->render($a['template'], array('data' => $data));
    }

}
