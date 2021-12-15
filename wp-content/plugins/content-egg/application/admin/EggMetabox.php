<?php

namespace ContentEgg\application\admin;

use ContentEgg\application\components\ModuleManager;
use ContentEgg\application\helpers\InputHelper;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\components\ContentManager;

/**
 * EggMetabox class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
class EggMetabox {

    private $app_params = array();

    public function __construct()
    {
        \add_action('add_meta_boxes', array($this, 'addMetabox'));
        \add_action('save_post', array($this, 'saveMeta'));
    }

    private function addAppParam($param, $value)
    {
        $this->app_params[$param] = $value;
    }

    private function getAppParams()
    {
        return $this->app_params;
    }

    public function addMetabox($post_type)
    {
        if (!in_array($post_type, GeneralConfig::getInstance()->option('post_types')))
            return;

        if (!ModuleManager::getInstance()->getModules(true))
        {
            \add_meta_box('content_meta_box', 'Content Egg', array($this, 'renderBlankMetabox'), $post_type, 'normal', 'high');
            return;
        }
        $this->modulesOptionsInit();
        $this->metadataInit();
        \add_meta_box('content_meta_box', 'Content Egg', array($this, 'renderMetabox'), $post_type, 'normal', 'high');
        $this->angularInit();
    }

    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function renderMetabox($post)
    {
        echo '<div ng-app="contentEgg" class="egg-container" id="content-egg" ng-cloak>';
        echo '<div ng-controller="ContentEggController" class="container-fluid">';

        PluginAdmin::render('metabox_general');

        foreach (ModuleManager::getInstance()->getModules(true) as $module)
        {
            $module->enqueueScripts();
            PluginAdmin::render('metabox_module', array('module_id' => $module->getId(), 'module' => $module));
        }
        echo '</div>';
        echo '</div>';
    }

    public function renderBlankMetabox($post)
    {
        _e('Настройте и активируйте модули Content Egg плагин.', 'content-egg');
    }

    private function metadataInit()
    {
        global $post;

        $modules = ModuleManager::getInstance()->getModules(true);

        // modules data
        $init_data = array();
        foreach ($modules as $module)
        {
            $post_meta = \get_post_meta($post->ID, ContentManager::META_PREFIX_DATA . $module->getId(), true);
            if (!$post_meta)
                continue;
            foreach ($post_meta as $key => $meta)
            {
                if ($meta['description'])
                    $post_meta[$key]['description'] = TextHelper::br2nl($meta['description']);
            }
            $init_data[$module->getId()] = array_values($post_meta);
        }
        $this->addAppParam('initData', $init_data);

        // keywords
        $init_keywords = array();
        foreach ($modules as $module)
        {
            if (!$module->isAffiliateParser())
                continue;
            $keywords_meta = \get_post_meta($post->ID, ContentManager::META_PREFIX_KEYWORD . $module->getId(), true);
            if (!$keywords_meta)
                continue;
            $init_keywords[$module->getId()] = $keywords_meta;
        }
        $this->addAppParam('initKeywords', $init_keywords);
    }

    private function modulesOptionsInit()
    {
        $init_options = array();
        foreach (ModuleManager::getInstance()->getModules(true) as $module)
        {
            $init_options[$module->getId()] = array();             
            foreach ($module->getConfigInstance()->options() as $option_name => $option)
            {
                if (isset($option['metaboxInit']) && $option['metaboxInit'])
                {
                    $init_options[$module->getId()][$option_name] = $module->config($option_name);
                }
                    
            }
        }
        $this->addAppParam('modulesOptions', $init_options);
    }

    private function angularInit()
    {
        // Justified gallery jquery plugin
        \wp_enqueue_script('justified-gallery', \ContentEgg\PLUGIN_RES . '/justified_gallery/jquery.justifiedGallery.min.js', array('jquery'), null, false);
        \wp_enqueue_style('justified-gallery', \ContentEgg\PLUGIN_RES . '/justified_gallery/justifiedGallery.min.css');

        // Angular core
        \wp_enqueue_script('angularjs', '//ajax.googleapis.com/ajax/libs/angularjs/1.4.0-rc.1/angular.min.js', array('jquery'), null, false);

        // ContentEgg angular application
        \wp_enqueue_style('contentegg-admin', \ContentEgg\PLUGIN_RES . '/css/admin.css');
        \wp_enqueue_script('angular-ui-bootstrap', \ContentEgg\PLUGIN_RES . '/app/vendor/angular-ui-bootstrap/ui-bootstrap-tpls-0.13.3.min.js', array('angularjs'), null, false);
        \wp_enqueue_script('angular-sortable', \ContentEgg\PLUGIN_RES . '/app/vendor/angular-sortable.js', array('angularjs', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-sortable'), null, false);
        \wp_register_script('contentegg-metabox-app', \ContentEgg\PLUGIN_RES . '/app/app.js', array('angularjs'), null, false);
        \wp_enqueue_script('contentegg-metabox-service', \ContentEgg\PLUGIN_RES . '/app/ModuleService.js', array('contentegg-metabox-app'), null, false);

        // Bootstrap
        \wp_enqueue_style('egg-bootstrap', \ContentEgg\PLUGIN_RES . '/bootstrap/css/egg-bootstrap.css');
        \wp_enqueue_script('bootstrap', \ContentEgg\PLUGIN_RES . '/bootstrap/js/bootstrap.min.js', array('jquery'), null, false);

        // ContentEgg application params
        $this->addAppParam('active_modules', ModuleManager::getInstance()->getModulesIdList(true));
        $this->addAppParam('nonce', \wp_create_nonce('contentegg-metabox'));

        \wp_localize_script('contentegg-metabox-app', 'contentegg_params', $this->getAppParams());
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function saveMeta($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (!isset($_POST['contentegg_nonce']))
            return;

        /*
         * why shouldn't i save metadata when its a revision?
         *
         * Apparently *_post_meta functions will automatically change
         * to parent post id if passed revision post id. So you might modify original post,
         * thinking you are modifying revision.
         * 
        if (\wp_is_post_revision($post_id))
            return;
         * 
         */

        \check_admin_referer('contentegg_metabox', 'contentegg_nonce');

        // Check the user's permissions.
        if ($_POST['post_type'] == 'page')
        {
            if (!current_user_can('edit_page', $post_id))
                return;
        } else
        {
            if (!current_user_can('edit_post', $post_id))
                return;
        }

        // need stripslashes? wp bug with revision post type?
        if (\wp_is_post_revision($post_id))
            $stripslashes = false;
        else
            $stripslashes = true;

        // keywords for automatic updates
        $keywords = InputHelper::post('cegg_updateKeywords', array(), $stripslashes);
        foreach ($keywords as $module_id => $keyword)
        {
            if (!ModuleManager::getInstance()->moduleExists($module_id) || !ModuleManager::getInstance()->isModuleActive($module_id))
                continue;

            $module = ModuleManager::getInstance()->factory($module_id);
            if (!$module->isAffiliateParser())
                continue;

            $keyword = \sanitize_text_field($keyword);
            if ($keyword)
            {
                \update_post_meta($post_id, ContentManager::META_PREFIX_KEYWORD . $module_id, $keyword);
            } else
            {
                \delete_post_meta($post_id, ContentManager::META_PREFIX_KEYWORD . $module_id);
            }
        }

        // save content data
        $content = InputHelper::post('cegg_data', array(), $stripslashes);
        if (!is_array($content))
            return;

        foreach ($content as $module_id => $data)
        {
            if (!ModuleManager::getInstance()->moduleExists($module_id) || !ModuleManager::getInstance()->isModuleActive($module_id))
                continue;

            $data = json_decode($data, true);
            $data = $this->dataPrepare($data);
            ContentManager::saveData($data, $module_id, $post_id);
        }
    }

    private function dataPrepare($data)
    {
        if (!is_array($data))
            return array();
        foreach ($data as $key => $d)
        {
            if ($key == 'description')
                $data[$key] = TextHelper::nl2br($d);
        }
        return $data;
    }

}
