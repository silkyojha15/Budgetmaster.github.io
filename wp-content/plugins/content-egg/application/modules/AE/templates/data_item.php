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
        <h3><?php echo esc_html($title); ?></h3>
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
                    <h2 class="media-heading"><?php echo $item['title']; ?><?php if ($item['manufacturer']): ?>, <?php echo esc_html($item['manufacturer']); ?><?php endif;?></h2>
                    
                    <?php if (!empty($item['extra']['data']['rating'])): ?>
                        <span class="rating"><?php echo str_repeat("<span>&#x2605</span>", $item['extra']['data']['rating']);
                        echo str_repeat("<span>☆</span>", 5 - $item['extra']['data']['rating']); ?></span>
                    <?php endif; ?>                

                    <div class="well-lg">

                        <div class="row">
                            <div class="col-md-6">
                                <?php if ($item['priceOld']): ?>
                                    <span class="text-muted">
                                        <strike>
                                            <?php echo TemplateHelper::formatPriceCurrency($item['priceOld'], $item['currencyCode']); ?>                           
                                        </strike></span><br>
                                <?php endif; ?>

                                <?php if ($item['price']): ?>
                                    <span class="cegg-price">
                                        <?php echo TemplateHelper::formatPriceCurrency($item['price'], $item['currencyCode']); ?>                           
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 text-center text-muted">
                                <a rel="nofollow" target="_blank" href="<?php echo $item['url']; ?>" class="btn btn-success"><?php _e('BUY THIS ITEM', 'content-egg-tpl'); ?></a>
                                <?php if (!empty($item['extra']['domain'])): ?>
                                    <br>
                                    <small><?php echo $item['extra']['domain']; ?></small>
                                <?php endif; ?>
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

                    <?php if ($item['extra']['features']): ?>
                        <h4><?php _e('Features', 'content-egg-tpl'); ?></h4>
                        <ul>
                            <?php foreach ($item['extra']['features'] as $feature): ?>
                                <li><?php echo '<strong>' . esc_html($feature['name']) . '</strong>' . ': ' . esc_html($feature['value']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if (!empty($item['extra']['comments'])): ?>
                        <h4><?php _e('User reviews', 'content-egg-tpl'); ?></h4>
                        <?php foreach ($item['extra']['comments'] as $key => $comment): ?>
                        <blockquote>
                            <?php if (!empty($comment['rating'])): ?>
                                <span class="rating_small">
                                    <?php echo str_repeat("<span>&#x2605</span>", (int) $comment['rating']); ?><?php echo str_repeat("<span>☆</span>", 5 - (int) $comment['rating']); ?>
                                </span>
                            <?php endif; ?>
                            <?php echo $comment['comment']; ?>
                        </blockquote>
                        <?php endforeach; ?>
                        <p style="text-align: right;">
                            <a class="btn btn-info" rel="nofollow" target="_blank" href="<?php echo esc_url($item['url']) ?>"><?php _e('View all reviews', 'content-egg-tpl'); ?></a>
                        </p>
                    <?php endif; ?>	
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
