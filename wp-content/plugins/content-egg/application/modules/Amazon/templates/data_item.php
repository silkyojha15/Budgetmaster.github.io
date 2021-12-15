<?php
/*
  Name: Product card
 */

__('Product card', 'content-egg-tpl');

use ContentEgg\application\helpers\TemplateHelper;
?>

<?php
\wp_enqueue_style('egg-bootstrap');
\wp_enqueue_style('content-egg-products');
?>


<div class="egg-container egg-item">
    
    <?php if ($title): ?>
        <h3><?php echo esc_html($title);?></h3>
    <?php endif; ?>
    
    <div class="products">

        <?php foreach ($items as $item): ?>
            <div class="row">
                <div class="col-md-5">
                    <?php if ($item['img']): ?>
                        <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>">                    
                            <img src="<?php echo $item['img']; ?>" alt="<?php echo esc_attr($item['title']); ?>" class="img-responsive" />
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-7">
                    <h2 class="media-heading"><?php echo $item['title']; ?></h2>
                    <?php if ((int) $item['rating'] > 0): ?>
                        <span class="rating"><?php echo str_repeat("<span>&#x2605</span>", (int) $item['rating']);
                        echo str_repeat("<span>☆</span>", 5 - (int) $item['rating']); ?></span>
                    <?php endif; ?>
                    <div class="well-lg">

                        <div class="row">
                            <div class="col-md-6">
                                <?php if ($item['priceOld']): ?>
                                    <span class="text-muted"><strike><small><?php echo $item['currency']; ?></small><?php echo TemplateHelper::price_format_i18n($item['priceOld']); ?></strike></span><br>
                                <?php endif; ?>
                                    
                                <?php if ($item['price']): ?>
                                    <span class="cegg-price"><small><?php echo $item['currency']; ?></small><?php echo TemplateHelper::price_format_i18n($item['price']); ?></span>
                                <?php elseif ($item['extra']['toLowToDisplay']): ?>
                                    <span class="text-muted"><?php _e('Too low to display', 'content-egg-tpl'); ?></span>
                                <?php endif; ?>
                                    
                                <?php if ((bool) $item['extra']['IsEligibleForSuperSaverShipping']): ?>
                                    <br><small class="text-muted"><?php _e('Free shipping', 'content-egg-tpl'); ?></small>
                                <?php endif; ?>                                
                                    
                                <span class="text-muted">
                                    <?php if (!empty($item['extra']['totalNew'])): ?>
                                        <br><?php echo $item['extra']['totalNew']; ?>
                                        <?php _e('new', 'content-egg-tpl'); ?> 
                                        <?php if($item['extra']['lowestNewPrice']): ?>
                                            <?php _e('from', 'content-egg-tpl'); ?> <?php echo $item['currency']; ?><?php echo TemplateHelper::price_format_i18n($item['extra']['lowestNewPrice']); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($item['extra']['totalUsed'])): ?>
                                        <br><?php echo $item['extra']['totalUsed']; ?>
                                        <?php _e('used', 'content-egg-tpl'); ?> <?php _e('from', 'content-egg-tpl'); ?>
                                        <?php echo $item['currency']; ?><?php echo TemplateHelper::price_format_i18n($item['extra']['lowestUsedPrice']); ?>
                                    <?php endif; ?>
                                </span>
                                <span class="text-muted">
                                    <br><small><?php _e('as of', 'content-egg-tpl'); ?> <?php echo TemplateHelper::getLastUpdateFormatted('Amazon'); ?></small>
                                </span>
                            </div>
                            <div class="col-md-6 text-center text-muted">
                                <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>" class="btn btn-success"><?php _e('BUY THIS ITEM', 'content-egg-tpl'); ?></a>
                                <br>
                                <small>Amazon</small>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if ($item['description']): ?>
                        <p><?php echo $item['description']; ?></p>
                    <?php endif; ?>

                        <?php if ($item['extra']['itemAttributes']['Feature']): ?>
                            <h3><?php _e('Features', 'content-egg-tpl'); ?></h3>
                            <ul>
                                <?php foreach ($item['extra']['itemAttributes']['Feature'] as $k => $feature): ?>
                                    <li><?php echo $feature; ?></li>
                                    <?php if($k >= 4) break; ?>                                    
                            <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if ($item['extra']['customerReviews']): ?>
                        <?php if (!empty($item['extra']['customerReviews']['reviews'])): ?>
                            <h3>
                                <?php _e('Customer reviews', 'content-egg-tpl'); ?>
                                <?php if (!empty($item['extra']['customerReviews']['TotalReviews'])):?>
                                
                                    <?php if ($link = TemplateHelper::getAmazonLink($item['extra']['itemLinks'], 'All Customer Reviews')): ?>
                                        <small>(<a rel="nofollow" target="_blank" href="<?php echo $link; ?>">
                                            <?php echo $item['extra']['customerReviews']['TotalReviews'];?> <?php _e('customer reviews', 'content-egg-tpl'); ?>
                                        </a>)</small>
                                    <?php endif; ?>
                                
                                <?php endif; ?>
                            </h3>
                            <?php foreach ($item['extra']['customerReviews']['reviews'] as $review): ?>
                                <div>
                                    <em><?php echo esc_html($review['Summary']); ?>, <small><?php echo date(get_option('date_format'), $review['Date']); ?></small></em>
                                    <span class="rating_small">
                                    <?php echo str_repeat("<span>&#x2605</span>", (int) $review['Rating']); ?><?php echo str_repeat("<span>☆</span>", 5 - (int) $review['Rating']); ?>
                                    </span>
                                </div>
                                <blockquote><?php echo esc_html($review['Content']); ?></blockquote>
                            <?php endforeach; ?>
                        <?php elseif ($item['extra']['customerReviews']['HasReviews'] == 'true'): ?>
                            <iframe src='<?php echo $item['extra']['customerReviews']['IFrameURL']; ?>' width='100%' height='500'></iframe>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($item['extra']['editorialReviews']): ?>
                        <?php foreach ($item['extra']['editorialReviews'] as $review): ?>
                            <h3><?php echo esc_html($review['Source']); ?></h3>
                            <p><?php echo $review['Content']; ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>