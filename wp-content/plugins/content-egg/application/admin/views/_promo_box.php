<div class="cegg-rightcol">
    <div class="cegg-box" style="margin-top: 95px;">
        <h2><?php _e('Работай, как профи', 'content-egg'); ?></h2>

        <img src="<?php echo ContentEgg\PLUGIN_RES; ?>/img/ce_pro_header.png" class="cegg-imgcenter" />        
        <a href="http://www.keywordrush.com/<?php if (!in_array(\get_locale(), array('ru_RU', 'uk'))) echo 'en/' ?>contentegg">
            <img src="<?php echo ContentEgg\PLUGIN_RES; ?>/img/ce_pro_coupon.png" class="cegg-imgcenter" />
        </a> 
        <h4><?php _e('Все включено: контент + монетизация.', 'content-egg'); ?></h4>

        <?php /*
        <h3><?php _e('Монетизация:', 'content-egg'); ?></h3>
        <ul>
            <li>Aliexpress</li>
            <?php if (\ContentEgg\application\admin\GeneralConfig::getInstance()->option('lang') == 'ru'): ?>
                <li>Где Слон</li>
                <li>Cityads</li>
                <li>Ozon.ru</li>
            <?php endif; ?>
            <li>eBay</li>
            <li>CJ Products</li>                
            <li>Affilinet</li>
            <li>Linkshare</li>
            <li>Shareasale</li>
            <li>Zanox</li>
            <li>ClickBank</li>
            <li>...</li>
        </ul>  

        <h3><?php _e('Контент модули:', 'content-egg'); ?></h3>
        <ul>
            <li><?php _e('Bing картинки', 'content-egg'); ?></li>
            <li><?php _e('Flickr фотографии', 'content-egg'); ?></li>
            <li><?php _e('Google книги', 'content-egg'); ?></li>
            <li><?php _e('Google новости', 'content-egg'); ?></li>
            <li><?php _e('Яндекс.Маркет', 'content-egg'); ?></li>
            <li>Twitter</li>
            <li><?php _e('ВКонтакте новости', 'content-egg'); ?></li>
            <li>...</li>
        </ul>
         * 
         */
        ?>
        <p>
            <a target="_blank" class="button-cegg-banner" href="http://www.keywordrush.com/<?php if (!in_array(\get_locale(), array('ru_RU', 'uk'))) echo 'en/' ?>contentegg">Get it now!</a>
        </p>
    </div>
</div>
