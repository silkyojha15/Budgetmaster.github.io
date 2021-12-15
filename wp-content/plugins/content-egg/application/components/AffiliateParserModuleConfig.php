<?php

namespace ContentEgg\application\components;

/**
 * ParserModuleConfig abstract class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
abstract class AffiliateParserModuleConfig extends ParserModuleConfig {

    public function options()
    {
        $options = array(
            'ttl' => array(
                'title' => __('Автоматическое обновление', 'content-egg'),
                'description' => __('Время жини кэша в секундах, через которое необходимо обновить товары, если задано ключевое слово для обновления. 0 - никогда не обновлять.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 2592000,
                'validator' => array(
                    'trim',
                    'absint',
                ),
                'section' => 'default',
            ),
        );

        if ($this->getModuleInstance()->isItemsUpdateAvailable())
        {
            $options['ttl_items'] = array(
                'title' => __('Обновить товары', 'content-egg'),
                'description' => __('Время в секундах, через которое необходимо обновить цену, наличие и некоторую другую информацию по товарам. 0 - никогда не обновлять.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 604800,
                'validator' => array(
                    'trim',
                    'absint',
                ),
                'section' => 'default',
            );
        }

        return
                array_merge(
                parent::options(), $options
        );
    }

}
