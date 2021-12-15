<?php
/*
  Name: Grid
 */

__('Grid', 'content-egg-tpl');

use ContentEgg\application\helpers\TemplateHelper;
?>

<?php
\wp_enqueue_style('egg-bootstrap');
\wp_enqueue_style('content-egg-products');
?>

<div class="egg-container egg-grid">
    <?php if ($title): ?>
        <h3><?php echo esc_html($title); ?></h3>
    <?php endif; ?>

    <div class="container-fluid">
        <?php $i = 0; ?>
        <?php foreach ($items as $item): ?>

            <a rel="nofollow" target="_blank" href="<?php echo esc_url($item['url']) ?>">
                <div class="col-md-4 productbox"> 
                    <?php if ($item['percentageSaved']): ?>
                        <div class="cegg-promotion">
                            <span class="cegg-discount">- <?php echo round($item['percentageSaved']); ?>%</span>
                        </div>				
                    <?php endif; ?>

                    <?php if ($item['img']): ?>
                        <?php $img = str_replace('.jpg', '._AC_UL250_SR250,250_.jpg', $item['img']) ?>
                        <img class="img-responsive" src="<?php echo esc_attr($img) ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                    <?php endif; ?>

                    <div class="producttitle">
                        <?php if ($item['manufacturer']): ?><?php echo esc_html($item['manufacturer']); ?><?php endif; ?>
                        <span><?php echo esc_html(TemplateHelper::truncate($item['title'], 80)); ?></span>                  
                    </div>

                    <?php if ((int) $item['rating'] > 0): ?>
                        <div>
                            <span class="rating_small"><?php echo str_repeat("<span>&#x2605</span>", (int) $item['rating']);echo str_repeat("<span>☆</span>", 5 - (int) $item['rating']);?></span>
                        </div>
                    <?php endif; ?>

                    <div class="productprice">
                        <?php if ($item['price']): ?>
                            <?php if ($item['priceOld']): ?><strike><?php echo $item['currency']; ?><?php echo TemplateHelper::price_format_i18n($item['priceOld']); ?></strike>&nbsp;<?php endif; ?>
                            <?php echo $item['currency']; ?><?php echo TemplateHelper::price_format_i18n($item['price']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </a>

            <?php $i++;
            if ($i % 3 == 0): ?>
                <div class="clearfix"></div>
            <?php endif; ?>
        <?php endforeach; ?>                
        <div class="row">
            <div class="col-md-12 text-right text-muted">
                <small><?php _e('Last updated on', 'content-egg-tpl'); ?> <?php echo TemplateHelper::getLastUpdateFormatted('Amazon'); ?></small>
            </div>
        </div>        
    </div>        
</div>