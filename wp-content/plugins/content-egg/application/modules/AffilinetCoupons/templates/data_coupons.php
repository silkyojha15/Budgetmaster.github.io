<?php
/*
  Name: Coupons
 */

__('Coupons', 'content-egg-tpl');

use ContentEgg\application\helpers\TemplateHelper;
?>

<?php
\wp_enqueue_style('egg-bootstrap');
\wp_enqueue_style('content-egg-products');
?>

<div class="egg-container">
    <?php if ($title): ?>
        <h3><?php echo esc_html($title); ?></h3>
    <?php endif; ?>

        <div class="egg-listcontainer">
            <?php foreach ($items as $item): ?>
                <div class="row-products">
                    <div class="col-md-10 col-sm-10 col-xs-12">

                        <strong><?php echo esc_html($item['title']); ?></strong><br>

                        <?php if ($item['description']): ?>
                            <div class="small"><?php echo $item['description']; ?></div>
                        <?php endif; ?>

                        <div class="row<?php if ($item['code']) echo ' egg-padding-top15'; ?>">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php if ($item['code']): ?>
                                    <?php _e('Coupon code:', 'content-egg-tpl'); ?>
                                    <span class="label label-info"><?php echo esc_html($item['code']); ?></span><br>
                                    <span class="text-muted"><em><?php _e('Ends:', 'content-egg-tpl'); ?> <?php echo date(get_option('date_format'), $item['endDate']); ?></em></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 text-right text-muted">
                                <img width="80" src="<?php echo esc_attr($item['img']);?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12 text-center">
                        <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>" class="btn btn-success"><?php _e('Shop Sale', 'content-egg-tpl'); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>        
        
        
</div>