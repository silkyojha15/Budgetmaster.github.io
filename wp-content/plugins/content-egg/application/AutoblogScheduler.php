<?php

namespace ContentEgg\application;

use ContentEgg\application\models\AutoblogModel;

/**
 * AutoblogScheduler class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
class AutoblogScheduler {

    const CRON_TAG = 'cegg_autoblog_cron';
    const AUTOBLOG_LIMIT = 1;

    public static function initAction()
    {
        \add_action(self::CRON_TAG, array('\\ContentEgg\\application\\AutoblogScheduler', 'runAutoblog'));
        //\add_action(self::CRON_TAG, array('self', 'runAutoblog'));
    }

    public static function addScheduleEvent($check_autoblogs = false)
    {
        // active autoblog exists?
        if ($check_autoblogs)
        {
            $total_autoblogs = AutoblogModel::model()->count('status = 1');
            if (!$total_autoblogs)
                return;
        }

        if (!\wp_next_scheduled(self::CRON_TAG))
        {
            \wp_schedule_event(time() + 900, 'hourly', self::CRON_TAG);
        }
    }

    public static function clearScheduleEvent()
    {
        if (\wp_next_scheduled(self::CRON_TAG))
        {
            \wp_clear_scheduled_hook(self::CRON_TAG);
        }
    }

    public static function runAutoblog()
    {
        @set_time_limit(900);
        $params = array(
            'select' => 'id',
            'where' => 'status = 1 AND (last_run IS NULL OR TIMESTAMPDIFF(SECOND, last_run, "' . current_time('mysql') . '") > run_frequency)',
            'order' => 'last_run  ASC',
            'limit' => self::AUTOBLOG_LIMIT
        );
        $autoblogs = AutoblogModel::model()->findAll($params);

        foreach ($autoblogs as $autoblog)
        {
            AutoblogModel::model()->run($autoblog['id']);
        }
    }

}
