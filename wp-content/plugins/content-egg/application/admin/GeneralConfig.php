<?php

namespace ContentEgg\application\admin;

use ContentEgg\application\components\Config;
use ContentEgg\application\Plugin;
use ContentEgg\application\admin\PluginAdmin;

/**
 * GeneralSettings class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
class GeneralConfig extends Config {

    public function page_slug()
    {
        return Plugin::slug() . '';
    }

    public function option_name()
    {
        return 'contentegg_options';
    }

    public function add_admin_menu()
    {
        \add_submenu_page(Plugin::slug, __('Настройки', 'content-egg') . ' &lsaquo; Content Egg', __('Настройки', 'content-egg'), 'manage_options', $this->page_slug, array($this, 'settings_page'));
    }

    public static function langs()
    {
        return array(
        'ar' => 'Arabic',
        'bg' => 'Bulgarian',
        'ca' => 'Catalan',
        //'zh_CN' => 'Chinese (simplified)',
        //'zh_TW' => 'Chinese (traditional)',
        'hr' => 'Croatian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'nl' => 'Dutch',
        'en' => 'English',
        'et' => 'Estonian',
        'tl' => 'Filipino',
        'fi' => 'Finnish',
        'fr' => 'French',
        'de' => 'German',
        'el' => 'Greek',
        'iw' => 'Hebrew',
        'hi' => 'Hindi',
        'hu' => 'Hungarian',
        'is' => 'Icelandic',
        'id' => 'Indonesian',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'lv' => 'Latvian',
        'lt' => 'Lithuanian',
        'ms' => 'Malay',
        'no' => 'Norwegian',
        'fa' => 'Persian',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'sr' => 'Serbian',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'es' => 'Spanish',
        'sv' => 'Swedish',
        'th' => 'Thai',
        'tr' => 'Turkish',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'vi' => 'Vietnamese',
        );
    }

    protected function options()
    {
        $post_types = get_post_types( array( 'public' => true ), 'names' );         
        if (isset($post_types['attachment']))
            unset($post_types['attachment']);
        return array(
            'lang' => array(
                'title' => __('Язык сайта', 'content-egg'),
                'description' => __('Модули, которые имеют поддержку мультиязычности, будут отдавать предпочтение контенту на этом языке. Также эта настройка указывает на язык для локализации шаблонов.', 'content-egg'),
                'dropdown_options' => self::langs(),
                'callback' => array($this, 'render_dropdown'),
                'default' => self::getDefaultLang(),
                'section' => 'default',
            ),
            'post_types' => array(
                'title' => 'Post Types',
                'description' => __('К каким типам постов добавить Content Egg metabox?', 'content-egg') . ' ' .
                    __('Эта настройка также показывает к каким типам постов применять автозаполнение на странице "Заполнить".', 'content-egg'),
                'checkbox_options' => $post_types,
                'callback' => array($this, 'render_checkbox_list'),
                'default' => array('post', 'page'),
                'section' => 'default',
            ),
            'filter_bots' => array(
                'title' => __('Фильтровать ботов', 'content-egg'),
                'description' => __('Боты не могут запускать парсеры.', 'content-egg') .
                    '<p class="description">' . __('Обновление цены, а также обновление выдачи по ключевому слову происходит при открытии страницы поста. Если мы определим по useragent, что на страницу зашел один из известных ботов, никакие парсеры запускаться не будут.', 'content-egg') . '</p>',
                'checkbox_options' => $post_types,
                'callback' => array($this, 'render_checkbox'),
                'default' => true,
                'section' => 'default',
            ),
        );
    }

    public static function getDefaultLang()
    {
        $locale = \get_locale();
        $lang = explode('_', $locale);
        if (array_key_exists($lang[0], self::langs()))
            return $lang[0];
        else
            return 'en';
    }

    public function settings_page()
    {
        PluginAdmin::render('settings', array('page_slug' => $this->page_slug()));
    }

}
