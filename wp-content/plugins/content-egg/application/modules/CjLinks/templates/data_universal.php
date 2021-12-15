<?php
/*
  Name: Universal
 */

__('Universal', 'content-egg-tpl');

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

    <?php if ($data = TemplateHelper::filterData($items, 'linkType', 'Text Link', true)): ?>
        <div class="egg-listcontainer">
            <?php foreach ($data as $item): ?>
                <div class="row-products">
                    <div class="col-md-10 col-sm-10 col-xs-12">

                        <strong><?php echo esc_html($item['title']); ?></strong><br>

                        <?php if ($item['description']): ?>
                            <div class="small"><?php echo $item['description']; ?></div>
                        <?php endif; ?>

                        <div class="row<?php if ($item['extra']['couponCode']) echo ' egg-padding-top15'; ?>">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php if ($item['extra']['couponCode']): ?>
                                    <?php _e('Coupon code:', 'content-egg-tpl'); ?>
                                    <span class="label label-info"><?php echo esc_html($item['extra']['couponCode']); ?></span><br>
                                    <span class="text-muted"><em><?php _e('Ends:', 'content-egg-tpl'); ?> <?php echo date(get_option('date_format'), $item['extra']['promotionEndDate']); ?></em></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 text-right text-muted">
                                <img title="<?php echo esc_attr($item['extra']['advertiserSite']); ?>" src="http://www.google.com/s2/favicons?domain=http://<?php echo esc_attr($item['extra']['advertiserSite']); ?>" alt="<?php echo esc_attr($item['extra']['advertiserName']); ?>" /> 
                                <small><?php echo esc_html($item['extra']['advertiserSite']); ?></small>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12 text-center">
                        <a title="<?php echo esc_attr($item['extra']['advertiserSite']); ?>" rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>" class="btn btn-success"><?php _e('Shop Sale', 'content-egg-tpl'); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($data = TemplateHelper::filterData($items, 'linkType', 'Banner', true)): ?>
        <div class="container-fluid">
            <?php $i = 0; ?>
            <div class="row">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6">
                        <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>">                    
                            <img src="<?php echo esc_attr($item['img']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="img-responsive" />
                        </a>
                    </div>
                    <?php $i++;
                    if ($i % 2 == 0): ?>
                        <div class="clearfix"></div>
                    <?php endif; ?>
    <?php endforeach; ?>
            </div>        
        </div>        
    <?php endif; ?>

        <?php if ($data = TemplateHelper::filterData($items, 'linkType', array('Text Link', 'Banner'), true, true)): ?>
        <div class="container-fluid">
    <?php foreach ($data as $item): ?>
                <div class="row">
                    <div class="col-md-12">
        <?php echo $item['extra']['linkHtml']; ?>
                    </div>        
                </div>            
        <?php endforeach; ?>
        </div>
<?php endif; ?>

</div>