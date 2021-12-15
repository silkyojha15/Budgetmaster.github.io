<?php
/*
 * Name: All offers list
 * Modules:
 * Module Types: PRODUCT
 * 
 */

__('All offers list', 'content-egg-tpl');

use ContentEgg\application\helpers\TemplateHelper;
use ContentEgg\application\helpers\TextHelper;
?>

<?php
\wp_enqueue_style('egg-bootstrap');
\wp_enqueue_style('content-egg-products');
?>
<div class="egg-container">
    <div class="egg-listcontainer">
        <?php foreach ($data as $module_id => $items): ?>
            <?php foreach ($items as $item): ?>
                <div class="row-products">
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <?php if ($item['img']): ?>
                            <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>">
                                <img src="<?php echo $item['img']; ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>">
                            <h4><?php echo TextHelper::truncate($item['title'], 100); ?></h4>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12 offer_price">
                        <?php if ($item['price']): ?>
                            <?php echo $item['currency']; ?><?php echo TemplateHelper::price_format_i18n($item['price']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>