<?php
/**
 * @version     1.0.3
 * @package     Components
 * @subpackage  admin.com_jvcounter.models
 * @link http://jeprodev.fr.nf
 * @copyright (C) 2009 - 2011
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of,
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . DIRECTORY_SEPARATOR. 'helper.php';
require_once('administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jvcounter'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'jvcounter.php');

$jvcIsSunday = $params->get('jvc_is_sunday', 1);
$jvcShowStatistics = $params->get('', 1);
$jvcShowToday = $params->get('jvc_show_today', 1);
$jvcShowYesterday = $params->get('jvc_show_yesterday', 1);
$jvcShowThisWeek = $params->get('jvc_show_this_week', 1);
$jvcShowLastWeek = $params->get('jvc_show_last_week', 1);
$jvcShowThisMonth = $params->get('jvc_show_this_month', 1);
$jvcShowLastMonth = $params->get('jvc_show_last_month', 1);
$jvcShowAll = $params->get('jvc_show_all', 1);
$jvcShowDigit = $params->get('jvc_show_digit', 1);
$jvcShowDigit = $params->get('jvc_show_digit', 1);
$showHrLine = $params->get('show_hr_line', 1);
$showOnlineVisits = $params->get('show_online_visits',1);
$jvcNumberOfDigits 	= $params->get('jvc_number_of_digits', 6);
$jvcDigitType = $params->get('jvc_digit_type', 'default');
$jvcShowOnlineVisitors = $params->get('jvc_show_online_visitors', 1);

$jvcStatsType = $params->get('jvc_stats_type', 'default');
$jvcCacheTime = (int) $params->get('jvc_cache_time', 1);

/** Get time offset from global configuration **/
$config	= JFactory::getConfig();
$offset	= $config->get('offset');

$isCache = JFactory::getCache();
$jvcCacheTime *= 60;
if( $jvcCacheTime < 0 || $jvcCacheTime > 3600) {
    $jvcCacheTime = DEFAULT_CACHE_TIMEOUT * 60;
}
$isCache->setLifeTime( $jvcCacheTime);

$now = time();

$dateTime = modJVCounterHelper::getDateTime($offset, $jvcIsSunday, $now);

/** computing visitors numbers */
// today's visits
$visitorsArray	= JvcounterModelJvcounter::getVisits();
$todayVisitors = $visitorsArray['visits'];

if( $jvcShowYesterday ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JvcounterModelJvcounter', 'getVisits'), $dateTime["local_yesterday_start"], $dateTime["local_day_start"]);
    }
    else {
        $visitorsArray = JvcounterModelJvcounter::getVisits( $dateTime["local_yesterday_start"], $dateTime["local_day_start"]);
    }
    $yesterdayVisitors = $visitorsArray['visits'];
}

if( $jvcShowThisWeek ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JvcounterModelJvcounter', 'getVisits'), $dateTime["local_week_start"], $dateTime["local_day_start"]);
    }
    else {
        $visitorsArray = JvcounterModelJvcounter::getVisits( $dateTime["local_week_start"], $dateTime["local_day_start"]);
    }
    $thisWeekVisitors = $visitorsArray["visits"];
    $thisWeekVisitors += $todayVisitors ;
}

if( $jvcShowLastWeek ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JvcounterModelJvcounter', 'getVisits'), $dateTime["local_last_week_start"], $dateTime["local_week_start"]);
    }
    else {
        $visitorsArray = JvcounterModelJvcounter::getVisits( $dateTime["local_last_week_start"], $dateTime["local_week_start"]);
    }
    $lastWeekVisitors = $visitorsArray['visits'];
}

if( $jvcShowThisMonth ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JvcounterModelJvcounter', 'getVisits'), $dateTime["local_month_start"], $dateTime["local_day_start"]);
    }
    else {
        $visitorsArray = JvcounterModelJvcounter::getVisits( $dateTime["local_month_start"], $dateTime["local_day_start"]);
    }
    $thisMonthVisitors = $visitorsArray['visits'];
    $thisMonthVisitors += $todayVisitors;
}

if( $jvcShowLastMonth ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JvcounterModelJvcounter', 'getVisits'), $dateTime["local_last_month_start"], $dateTime["local_month_start"]);
    }
    else {
        $visitorsArray = JvcounterModelJvcounter::getVisits( $dateTime["local_last_month_start"], $dateTime["local_month_start"]);
    }
    $lastMonthVisitors = $visitorsArray['visits'];
}

if( $isCache ) {
    $visitorsArray = $isCache->call(array('JvcounterModelJvcounter', 'getVisits'), 0, $dateTime["local_day_start"]);
}
else {
    $visitorsArray = JvcounterModelJvcounter::getVisits( 0, $dateTime["local_day_start"]);
}
$allVisitors = $visitorsArray['visits'];
$allVisitors += $todayVisitors ;

$online_time	= ONLINE_DEFAULT_TIME;
$online_time	*=	60;

if( $jvcShowOnlineVisitors ) {
    $onlineVisitorsArray =  JvcounterModelJvcounter::getVisits( 0, 0, $online_time);
    $onlineVisits = $onlineVisitorsArray['visits'];
}

require JModuleHelper::getLayoutPath('mod_jvcounter', $params->get('layout', 'default'));