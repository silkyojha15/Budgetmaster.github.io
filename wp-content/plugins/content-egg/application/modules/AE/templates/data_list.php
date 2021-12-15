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
                        <?php $img = str_replace('/spare_covers/', '/c200/', $item['img']);?>
                        <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>">
                            <img src="<?php echo $img; ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-7 col-sm-7 col-xs-12 cegg-desc-cell">
                    <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>">
                        <h4><?php echo $item['title']; ?></h4>
                    </a>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 offer_price cegg-price-cell">
                    <?php if ($item['priceOld']): ?>
                        <span class="text-muted"><strike><?php echo TemplateHelper::formatPriceCurrency($item['priceOld'],  $item['currencyCode']); ?></strike></span><br>
                    <?php endif; ?>
                    
                    <?php if ($item['price']): ?>
                        <?php echo TemplateHelper::formatPriceCurrency($item['price'],  $item['currencyCode']); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>