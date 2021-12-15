<?php

namespace ContentEgg\application;

use ContentEgg\application\admin\GeneralConfig;

/**
 * Plugin class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2016 keywordrush.com
 */
class Plugin {

    const version = '2.6.1';
    const db_version = 9;    
    const wp_requires = '4.2.2';
    const slug = 'content-egg';
    const api_base = 'http://www.keywordrush.com/api/v1';
    const product_id = 302;

    private static $instance = null;
    private static $is_pro = null;

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self;

        return self::$instance;
    }

    private function __construct()
    {
        $this->loadTextdomain();
        if (self::isFree() || (self::isPro() && self::isActivated()))
        {
            \add_action('wp_enqueue_scripts', array($this, 'registerScripts'));
            EggShortcode::getInstance();
            BlockShortcode::getInstance();
            ModuleViewer::getInstance()->init();
            ModuleUpdater::getInstance()->init();
            AutoblogScheduler::initAction();
            
        }
        if (Plugin::isPro() && Plugin::isActivated())
        {
            new Autoupdate(Plugin::version(), plugin_basename(\ContentEgg\PLUGIN_FILE), Plugin::getApiBase(), Plugin::slug);
        }
        
        //AutoblogScheduler::runAutoblog();
    }

    public function registerScripts()
    {
        \wp_register_style('egg-bootstrap', \ContentEgg\PLUGIN_RES . '/bootstrap/css/egg-bootstrap.css');
        \wp_register_script('bootstrap', \ContentEgg\PLUGIN_RES . '/bootstrap/js/bootstrap.min.js', array('jquery'), null, false);
        \wp_register_style('content-egg-products', \ContentEgg\PLUGIN_RES . '/css/products.css');
    }

    static public function version()
    {
        return self::version;
    }

    static public function slug()
    {
        return self::slug;
    }

    public static function getApiBase()
    {
        return self::api_base;
    }

    public static function isFree()
    {
        return !self::isPro();
    }

    public static function isPro()
    {
        if (self::$is_pro === null)
        {
            if (class_exists("\\ContentEgg\\application\\Autoupdate", true))
                self::$is_pro = true;
            else
                self::$is_pro = false;
        }
        return self::$is_pro;
    }

    public static function isActivated()
    {
        if (self::isPro() && \ContentEgg\application\admin\LicConfig::getInstance()->option('license_key'))
            return true;
        else
            return false;
    }

    private function loadTextdomain()
    {
        // plugin backend
        $locale = \get_locale();
        $mo_file = \ContentEgg\PLUGIN_PATH . 'languages/content-egg-' . $locale . '.mo';
        if (file_exists($mo_file) && is_readable($mo_file))
            \load_textdomain('content-egg', $mo_file);
        elseif (!in_array($locale, array('ru_RU', 'uk')))
            \load_textdomain('content-egg', \ContentEgg\PLUGIN_PATH . 'languages/content-egg-en_US.mo');

        // frontend templates
        $lang = GeneralConfig::getInstance()->option('lang');
        $lang = strtoupper($lang);
        $mo_file = \ContentEgg\PLUGIN_PATH . 'languages/tpl/content-egg-tpl-' . $lang . '.mo';
        if (file_exists($mo_file) && is_readable($mo_file))
            $v = \load_textdomain('content-egg-tpl', $mo_file);
    }
    
    

}
