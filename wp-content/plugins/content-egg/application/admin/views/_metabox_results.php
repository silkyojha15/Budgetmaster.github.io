<div class="data_results" ng-if="models.<?php echo $module_id; ?>.added.length">
    <div ui-sortable="{ 'ui-floating': true }" ng-model="models.<?php echo $module_id; ?>.added" class="row">
        <div class="col-md-12 added_data" ng-repeat="data in models.<?php echo $module_id; ?>.added">
            <div class="row" style="padding: 5px;">
                <div class="col-md-1" ng-if="data.img">
                    <img ng-src="{{data.img}}" class="img-responsive" style="max-height: 100px;" />
                </div>
                <div ng-class="data.img ? 'col-md-10' : 'col-md-11'">
                    <input type="text" placeholder="<?php _e('Заголовок', 'content-egg'); ?>" ng-model="data.title" class="form-control" style="margin-bottom: 5px;">
                    <textarea type="text" placeholder="<?php _e('Описание', 'content-egg'); ?>" rows="2" ng-model="data.description" class="col-sm-12 "></textarea>
                </div>
                <div class="col-md-1">
                    <a href="{{data.url}}" target="_blank"><?php _e('Перейти', 'content-egg'); ?></a><br><br>
                    <a ng-click="delete(data, '<?php echo $module_id; ?>')"><?php _e('Удалить', 'content-egg'); ?></a>                                
                </div>  
            </div>
        </div>
    </div>
</div>