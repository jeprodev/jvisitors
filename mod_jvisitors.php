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


$jvcIsSunday = $params->get('j_visitors_is_sunday', 1);
$jvcShowStatistics = $params->get('', 1);
$jvcShowToday = $params->get('j_visitors_show_today', 1);
$jvcShowYesterday = $params->get('j_visitors_show_yesterday', 1);
$jvcShowThisWeek = $params->get('j_visitors_show_this_week', 1);
$jvcShowLastWeek = $params->get('j_visitors_show_last_week', 1);
$jvcShowThisMonth = $params->get('j_visitors_show_this_month', 1);
$jvcShowLastMonth = $params->get('j_visitors_show_last_month', 1);
$jvcShowAll = $params->get('j_visitors_show_all', 1);
$jvcShowDigit = $params->get('j_visitors_show_digit', 1);
$jvcShowDigit = $params->get('j_visitors_show_digit', 1);
$showHrLine = $params->get('j_visitors_show_hr_line', 1);
$showOnlineVisits = $params->get('j_visitors_show_online_visits',1);
$jvcNumberOfDigits 	= $params->get('jvc_number_of_digits', 6);
$jvcDigitType = $params->get('j_visitors_digit_type', 'default');
$jvcShowOnlineVisitors = $params->get('j_visitors_show_online_visitors', 1);

$jvcStatsType = $params->get('j_visitors_stats_type', 'default');
$jvcCacheTime = (int) $params->get('j_visitors_cache_time', 1);

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

$dateTime = ModJVisitorCounterHelper::getDateTime($offset, $jvcIsSunday, $now);

/** computing visitors numbers */
// today's visits
$visitorsArray	= JVisitorModelJVisitor::getVisits();
$todayVisitors = $visitorsArray['visits'];

if( $jvcShowYesterday ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JVisitorModelJVisitor', 'getVisits'), $dateTime["local_yesterday_start"], $dateTime["local_day_start"]);
    }
    else {
        $visitorsArray = JVisitorModelJVisitor::getVisits( $dateTime["local_yesterday_start"], $dateTime["local_day_start"]);
    }
    $yesterdayVisitors = $visitorsArray['visits'];
}

if( $jvcShowThisWeek ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JVisitorModelJVisitor', 'getVisits'), $dateTime["local_week_start"], $dateTime["local_day_start"]);
    }
    else {
        $visitorsArray = JVisitorModelJVisitor::getVisits( $dateTime["local_week_start"], $dateTime["local_day_start"]);
    }
    $thisWeekVisitors = $visitorsArray["visits"];
    $thisWeekVisitors += $todayVisitors ;
}

if( $jvcShowLastWeek ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JVisitorModelJVisitor', 'getVisits'), $dateTime["local_last_week_start"], $dateTime["local_week_start"]);
    }
    else {
        $visitorsArray = JVisitorModelJVisitor::getVisits( $dateTime["local_last_week_start"], $dateTime["local_week_start"]);
    }
    $lastWeekVisitors = $visitorsArray['visits'];
}

if( $jvcShowThisMonth ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JVisitorModelJVisitor', 'getVisits'), $dateTime["local_month_start"], $dateTime["local_day_start"]);
    }
    else {
        $visitorsArray = JVisitorModelJVisitor::getVisits( $dateTime["local_month_start"], $dateTime["local_day_start"]);
    }
    $thisMonthVisitors = $visitorsArray['visits'];
    $thisMonthVisitors += $todayVisitors;
}

if( $jvcShowLastMonth ) {
    if( $isCache ) {
        $visitorsArray = $isCache->call(array('JVisitorModelJVisitor', 'getVisits'), $dateTime["local_last_month_start"], $dateTime["local_month_start"]);
    }
    else {
        $visitorsArray = JVisitorModelJVisitor::getVisits( $dateTime["local_last_month_start"], $dateTime["local_month_start"]);
    }
    $lastMonthVisitors = $visitorsArray['visits'];
}

if( $isCache ) {
    $visitorsArray = $isCache->call(array('JVisitorModelJVisitor', 'getVisits'), 0, $dateTime["local_day_start"]);
}
else {
    $visitorsArray = JVisitorModelJVisitor::getVisits( 0, $dateTime["local_day_start"]);
}
$allVisitors = $visitorsArray['visits'];
$allVisitors += $todayVisitors ;

$online_time	= ONLINE_DEFAULT_TIME;
$online_time	*=	60;

if( $jvcShowOnlineVisitors ) {
    $onlineVisitorsArray =  JVisitorModelJVisitor::getVisits( 0, 0, $online_time);
    $onlineVisits = $onlineVisitorsArray['visits'];
}

require JModuleHelper::getLayoutPath('mod_jvisitors', $params->get('layout', 'default'));