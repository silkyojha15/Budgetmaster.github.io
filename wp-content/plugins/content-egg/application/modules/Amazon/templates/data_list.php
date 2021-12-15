<?php
/*
  Name: List
 */

__('List', 'content-egg-tpl');

use ContentEgg\application\helpers\TemplateHelper;
?>

<?php
\wp_enqueue_style('egg-bootstrap');
\wp_enqueue_style('content-egg-products');
?>

<div class="egg-container egg-list">
    <?php if ($title): ?>
        <h3><?php echo esc_html($title); ?></h3>
    <?php endif; ?>

    <div class="egg-listcontainer">

        <?php foreach ($items as $item): ?>
            <div class="row-products">
                <div class="col-md-2 col-sm-2 col-xs-12 cegg-image-cell">
                    <?php if ($item['img']): ?>
                        <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>">
                            <img src="<?php echo $item['img']; ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12 cegg-desc-cell">
                    <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>">
                        <h4><?php echo $item['title']; ?></h4>
                    </a>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12 offer_price cegg-price-cell">
                    <?php if ($item['priceOld']): ?>
                        <span class="text-muted"><strike><small><?php echo $item['currency']; ?></small><?php echo TemplateHelper::price_format_i18n($item['priceOld']); ?></strike></span><br>
                    <?php endif; ?>

                    <?php if ($item['price']): ?>
                        <?php echo $item['currency']; ?><?php echo TemplateHelper::price_format_i18n($item['price']); ?>
                    <?php elseif ($item['extra']['toLowToDisplay']): ?>
                        <span class="text-muted"><?php _e('Too low to display', 'content-egg-tpl'); ?></span>
                    <?php endif; ?>

                    <?php if ((bool) $item['extra']['IsEligibleForSuperSaverShipping']): ?>
                        <br><span class="text-muted"><?php _e('Free shipping', 'content-egg-tpl'); ?></span>
                    <?php endif; ?>                                
                </div>
            </div>
        <?php endforeach; ?>
    </div>   
    <div class="row">
        <div class="col-md-12 text-right text-muted">
            <small><?php _e('Last updated on', 'content-egg-tpl'); ?> <?php echo TemplateHelper::getLastUpdateFormatted('Amazon'); ?></small>
        </div>
    </div>        
</div>