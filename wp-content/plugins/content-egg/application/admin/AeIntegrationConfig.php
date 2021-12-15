<?php

namespace ContentEgg\application\admin;

use ContentEgg\application\components\Config;
use ContentEgg\application\Plugin;

/**
 * AeIntegrationConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2016 keywordrush.com
 */
class AeIntegrationConfig extends Config {

    const MIN_AE_VERSION = '7.1.0';

    public function page_slug()
    {
        return Plugin::slug . '-ae-integration';
    }

    public function option_name()
    {
        return Plugin::slug . '_ae_integration';
    }

    public function add_admin_menu()
    {
        \add_submenu_page(Plugin::slug, __('AE интеграция', 'content-egg') . ' &lsaquo; Content Egg', __('AE интеграция', 'content-egg'), 'manage_options', $this->page_slug(), array($this, 'settings_page'));
    }

    protected function options()
    {
        if (!self::isAEIntegrationPosible())
            return array();

        $aff_egg_modules = \Keywordrush\AffiliateEgg\ShopManager::getInstance()->getSearchableItemsList(true, false, true);
        return array(
            'modules' => array(
                'title' => __('Активировать модули', 'content-egg'),
                'description' => '',
                'checkbox_options' => $aff_egg_modules,
                'callback' => array($this, 'render_checkbox_list'),
                'default' => array(),
                'section' => 'default',
            ),
        );
    }

    public function settings_page()
    {
        PluginAdmin::render('ae_integration', array('page_slug' => $this->page_slug()));
    }

    public static function isAEIntegrationPosible()
    {
        if (!class_exists('\Keywordrush\AffiliateEgg\ShopManager'))
            return false;

        $v = \Keywordrush\AffiliateEgg\AffiliateEgg::version();

        if (version_compare(self::MIN_AE_VERSION, $v, '>'))
            return false;

        return true;
    }

}
