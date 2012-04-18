<?php
/**
 * Time Clock
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GPL v2
 * @link        http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Friday, March 04, 2011 / 02:43 PM GMT+1 mknox
 * @edited      $Date: 2011-05-03 12:36:13 +0200 (Tue, 03 May 2011) $ $Author: mknox $
 * @version     $Revision: 2 $
 *
 * @category    Classes
 * @package     Time Clock
 *
 * @svn         $URL: file:///C:/Users/mknox/Documents/My%20Dev/Local%20SVN/timeClock/upload/includes/classes/timeClock.class.php $
 */

class timeClock
{
    private $_jqGrid;

    function __construct()
    {
        global $jqGrid;
        $this->_jqGrid = $jqGrid;
    }

    function fetchConfig()
    {
        $sql = "SELECT * FROM `config`";
        $res = mysql_query($sql) OR die(mysql_error());

        if(mysql_num_rows($res) > 0) {
            $data = array();
            while($row = mysql_fetch_assoc($res)) {
                $data[] = $row;
            }

            return $data;
        }
    }

    function defineConfig()
    {
        $data = $this->fetchConfig();

        if(!empty($data)) {
            $count = count($data);
            for ($i = 0; $i < $count; $i++) {
                define($data[$i]['key'], $data[$i]['value']);
            }
        }
    }

    function isClockedIn()
    {
        $sql    = "SELECT * FROM `records` WHERE ";
        $sql   .= "`outTimestamp` IS NULL ";

        $res = mysql_query($sql) OR die(mysql_error());

        if(mysql_num_rows($res) > 0) {

            $data = array();

            while($row = mysql_fetch_assoc($res)) {
                $data[] = $row;
            }

            return $data;
        }
    }

    function clockOut($Id)
    {
        $sql    = "UPDATE `records` SET ";
        $sql   .= "`outTimestamp` = '".time()."' WHERE ";
        $sql   .= "`Id` = '".mysql_real_escape_string($Id)."' ";
        $sql   .= "LIMIT 1";

        $res = mysql_query($sql) OR die(mysql_error());

        exit('OK');
    }

    function clockIn()
    {
        $sql    = "INSERT INTO `records` ( ";
        $sql   .= "`month` ,";
        $sql   .= "`day` ,";
        $sql   .= "`year` ,";
        $sql   .= "`week` ,";
        $sql   .= "`inTimestamp` ";
        $sql   .= ") ";
        $sql   .= "VALUES (";
        $sql   .= "'".date('m')."' ,";
        $sql   .= "'".date('d')."' ,";
        $sql   .= "'".date('Y')."' ,";
        $sql   .= "'".date('W')."' ,";
        $sql   .= "'".time()."' ";
        $sql   .= ");";

        $res = mysql_query($sql) OR die(mysql_error());

        exit('OK');
    }
}