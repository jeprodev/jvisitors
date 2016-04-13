<?php
/**
 * @version     1.0.3
 * @package     modules
 * @subpackage  mod_jvcounter
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

define('ONLINE_DEFAULT_TIME', 1);
define('DEFAULT_CACHE_TIMEOUT', 1);
define('STATISTIC_ICON_PATH','modules/mod_jvcounter/images/stats');
define('DIGITS_ICON_PATH','modules/mod_jvcounter/images/digits');

require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jvcounter'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'jvcounter.php');

class modJVCounterHelper {
    /** method to show the number of visitors as image
     * @param string $digittype
     * @param $digit
     * @return string $img :html img tag with the digit image to display
     */
    static function showDigitImage($digittype = "default", $digit){
       $img = '<img src="'.JURI::base().DIGITS_ICON_PATH.'/'.$digittype.'/'.$digit.'.png" alt="jvcounter" />';
       return $img;
    }

    /**
     * method to display the statics row
     * @param string $statstype
     * @param $image
     * @param string $timeline
     * @param string $time
     * @param string $visitors
     * @return string $row : html tr tag that displays a static row
     */
    static function showStatisticsRows($statstype="default", $image, $timeline="", $time="", $visitors="" ){
        $ret = '<tr> <td>';
        $ret .= '<img src="'.JURI::base().STATISTIC_ICON_PATH.'/'.$statstype .'/'. $image . '.png"';
        $ret .= ' alt="jvcounter" title="'. $timeline . '" /></td>' ;
        $ret .= '<td>'. $time . '</td><td class="pull-right">'.$visitors .'</td></tr>';

        return $ret;
    }

    /**
     * method to show the timeline
     * @param int $timeBegin
     * @param int $timeEnd
     * @param int $offset
     * @param string $formattime
     * @param string $spacer
     * @return string
     */
    static function showTimeLine( $timeBegin = 0, $timeEnd = 0, $offset = 0, $formatTime = "%Y-%m-%d", $spacer = " -&gt; " ) {
        $timeBegin	=	(int) $timeBegin;
        $timeEnd	=	(int) $timeEnd;
        $offset		=	(float) $offset;

        $str		=	"";

        if ( $timeBegin ){
            $time	= JFactory::getDate( $timeBegin );
            /*$time->setTimeZone( $offset );*/

            $str .=	$time->format( $formatTime ) ;

            if ( $timeEnd ){
                $time	= JFactory::getDate( $timeEnd );
                $time->setOffset( $offset );

                $str	.=	$spacer;
                $str	.=	$time->toFormat( $formatTime ) ;
            }
        }
        return $str;
    }

    static function getDigits($visitors, $numberOfDigit=0) {
        $strLen	= strlen( $visitors );

        $arr = array();
        $diff = $numberOfDigit - $strLen;

        while ( $diff > 0) {
            array_push( $arr, 0);
            $diff--;
        }

        $arrNumber = str_split( $visitors );
        $arr = array_merge( $arr, $arrNumber);

        return $arr;
    }

    static function getDateTime($offset, $isSunday, $now){
        $datetime = JvcounterModelJvcounter::getStartTime($offset, $isSunday, $now);
        return $datetime;
    }

    static function getTimeZone(){
        $userTz = JFactory::getUser()->getParam('timezone');
        $timeZone = JFactory::getConfig()->get('offset');

        if($userTz) $timeZone = $userTz;

        return new DateTimeZone($timeZone);
    }
}