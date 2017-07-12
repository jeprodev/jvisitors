<?php
/**
 * @version     1.0.3
 * @package     Components
 * @subpackage  com_jvcounter
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
$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_jvisitors/assets/css/jvcounter.css');
?>
<div id="jv-counter">
    <div id="jv-counter-title" >
        <h4><?php echo JText::_("MOD_VISITOR_COUNTER_TITLE"); ?></h4>
        <div id="jv-counter-digit-sm">
            <?php
            $arr = ModJVisitorCounterHelper::getDigits($allVisitors, $jvcNumberOfDigits);
            $digit_img = '';
            foreach( $arr as $digit ){
                $digit_img .= ModJVisitorCounterHelper::showDigitImage( $jvcDigitType, $digit);
            }
            echo $digit_img;
            ?>
        </div>
    </div>
    <?php if( $jvcShowStatistics ) {?>
    <div id="jv-counter-stats">
        <table id="counter-stats" >
            <tbody>
            <?php $statistics = "";
            if( $jvcShowToday ){
                $timeLine = ModJVisitorCounterHelper::showTimeLine( $dateTime["local_day_start"], 0, $offset);
                $statistics .= ModJVisitorCounterHelper::showStatisticsRows($jvcStatsType, 't_visits', $timeLine, JText::_('MOD_VISITOR_COUNTER_TODAY_LABEL'), $todayVisitors);
            }
            if ( $jvcShowYesterday ) {
                $timeLine = ModJVisitorCounterHelper::showTimeLine( $dateTime["local_yesterday_start"], 0, $offset);
                $statistics .= ModJVisitorCounterHelper::showStatisticsRows($jvcStatsType, 'y_visits', $timeLine, JText::_('MOD_VISITOR_COUNTER_YESTERDAY_LABEL'), $yesterdayVisitors);
            }
            if ( $jvcShowThisWeek ) {
                $timeLine = ModJVisitorCounterHelper::showTimeLine( $dateTime["local_week_start"], 0, $offset);
                $statistics .= ModJVisitorCounterHelper::showStatisticsRows($jvcStatsType, 'w_visits', $timeLine, JText::_('MOD_VISITOR_COUNTER_THIS_WEEK_LABEL'), $thisWeekVisitors);
            }
            if ( $jvcShowLastWeek ) {
                $timeLine = ModJVisitorCounterHelper::showTimeLine( $dateTime["local_last_week_start"], 0, $offset);
                $statistics .= ModJVisitorCounterHelper::showStatisticsRows($jvcStatsType, 'lw_visits', $timeLine, JText::_('MOD_VISITOR_COUNTER_LAST_WEEK_LABEL'), $lastWeekVisitors);
            }
            if ( $jvcShowThisMonth) {
                $timeLine = ModJVisitorCounterHelper::showTimeLine( $dateTime["local_month_start"], 0, $offset);
                $statistics .= ModJVisitorCounterHelper::showStatisticsRows($jvcStatsType, 'm_visits', $timeLine, JText::_('MOD_VISITOR_COUNTER_THIS_MONTH_LABEL'), $thisMonthVisitors);
            }
            if ( $jvcShowLastMonth) {
                $timeLine = ModJVisitorCounterHelper::showTimeLine($dateTime["local_last_month_start"], 0, $offset);
                $statistics .= ModJVisitorCounterHelper::showStatisticsRows($jvcStatsType, 'lm_visits', $timeLine, JText::_('MOD_VISITOR_COUNTER_LAST_MONTH_LABEL'), $lastMonthVisitors);
            }
            if ( $jvcShowAll ) {
                $timeLine = ModJVisitorCounterHelper::showTimeLine($dateTime["local_yesterday_start"], 0, $offset);
                $statistics .= ModJVisitorCounterHelper::showStatisticsRows($jvcStatsType, 'all_visits', $timeLine, JText::_('MOD_VISITOR_COUNTER_ALL_LABEL'), $allVisitors);
            }
            echo $statistics;
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if( $jvcShowDigit ) { ?>
    <div id="jv-counter-digit">
        <?php
            $arr = ModJVisitorCounterHelper::getDigits($allVisitors, $jvcNumberOfDigits);
            $digit_img = '';
            foreach( $arr as $digit ){
                $digit_img .= ModJVisitorCounterHelper::showDigitImage($jvcDigitType, $digit);
            }
            echo $digit_img;
        ?>
    </div>
    <?php } ?>
    <?php if( $showHrLine ){?>
        <hr />
    <?php } ?>
    <?php if( $showOnlineVisits ){  ?>
    <div id="jvcounter-online">
        <?php
        $onlineVisitsCounts = $onlineVisitorsArray['guests'] + $onlineVisitorsArray['bots'] + $onlineVisitorsArray['members'];
        $onlineVisits = ($onlineVisitsCounts > 1 ? JText::_('MOD_VISITOR_COUNTER_THERE_ARE') : JText::_('MOD_VISITOR_COUNTER_THERE_IS')) .': &nbsp;'.$onlineVisitsCounts .' ' . JText::_('MOD_VISITOR_COUNTER_ONLINE_LABEL').'<br />';
        $online_str = '';

        if ( $onlineVisitorsArray['guests']) {
            $online_str .=  $onlineVisitorsArray['guests'].' '.JText::_('MOD_VISITOR_COUNTER_GUESTS_LABEL' ) . '<br />';
        }
        if ( $onlineVisitorsArray['members']) {
            $online_str .= $onlineVisitorsArray['members'].' '.JText::_('MOD_VISITOR_COUNTER_MEMBERS_LABEL') . '<br />';
        }
        if ( $onlineVisitorsArray['bots']) {
            $online_str .= $onlineVisitorsArray['bots'].' '. JText::_('MOD_VISITOR_COUNTER_BOTS_LABEL' ) . '<br />';
        }
        echo $onlineVisits;
        echo $online_str;
        ?>
    </div>
    <?php } ?>
</div>