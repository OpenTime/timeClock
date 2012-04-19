<?php
/**
 * Time Clock
 * Subgrid
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GPL v2
 * @link        http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Thursday, March 31, 2011 / 12:52 PM GMT+1 mknox
 * @edited      $Date$ $Author$
 * @version     $Revision$
 *
 * @package     Time Clock
 */

define('THIS_PAGE', 'subgrid');
require_once('includes/config.php');

if(empty($_POST)) {
    header('Location: '.GetServerURL().'/clockin');
}

// get the id passed automatically to the request
$id			= $_POST['undefined'];
$dayPost 	= substr( $_POST['day'], 0, 2 );

// construct the query
$sql    = "SELECT * FROM `records` WHERE `id` = ".mysql_real_escape_string($id);
$res    = mysql_query($sql) OR die(mysql_error());
$data   = mysql_fetch_assoc($res);

$day    = date('N', $data['inTimestamp']);
$dayMo  = date('j', $data['inTimestamp']);
$dayYr  = date('z', $data['inTimestamp']);
$week   = date('W', $data['inTimestamp']);
$month  = $data['month'];
$year   = $data['year'];

// week logic
$sql    = "SELECT COUNT(DISTINCT `day`) AS `count` FROM `records` WHERE ";
$sql   .= "`week` = '".mysql_real_escape_string($week)."' ";
$sql   .= "AND `year` = '".mysql_real_escape_string($year)."' ";
$sql   .= "AND `day` <= '".mysql_real_escape_string( $dayPost )."' ";
$res    = mysql_query($sql) OR die(mysql_error());

$data   = mysql_fetch_assoc($res);
$count  = $data['count'];

$sql    = "SELECT * FROM `records` WHERE ";
$sql   .= "`week` = '".mysql_real_escape_string($week)."' ";
$sql   .= "AND `year` = '".mysql_real_escape_string($year)."' ";
$sql   .= "AND `day` <= '".mysql_real_escape_string( $dayPost )."' ";
$res    = mysql_query($sql) OR die(mysql_error());

$data       = array();
$weekHours  = array();

while($row = mysql_fetch_assoc($res)) {
    if(strlen($row['outTimestamp'])) {
        $row['totalHours']  = ($row['outTimestamp'] - $row['inTimestamp']);
        $weekHours[]        = $row['totalHours'];
        $weekDayCount       = $count;
    } else {
        $row['totalHours']  = (time() - $row['inTimestamp']);
        $weekHours[]        = $row['totalHours'];
        $weekDayCount       = $count;
    }
}

// month logic
$sql    = "SELECT COUNT(DISTINCT `day`) AS `count` FROM `records` WHERE ";
$sql   .= "`month` = '".mysql_real_escape_string($month)."' ";
$sql   .= "AND `year` = '".mysql_real_escape_string($year)."' ";
$sql   .= "AND `day` <= '".mysql_real_escape_string( $dayPost )."' ";
$res    = mysql_query($sql) OR die(mysql_error());

$data   = mysql_fetch_assoc($res);
$count  = $data['count'];

$sql    = "SELECT * FROM `records` WHERE ";
$sql   .= "`month` = '".mysql_real_escape_string($month)."' ";
$sql   .= "AND `year` = '".mysql_real_escape_string($year)."' ";
$sql   .= "AND `day` <= '".mysql_real_escape_string( $dayPost )."' ";
$res    = mysql_query($sql) OR die(mysql_error());

$data       = array();
$monthHours = array();

while($row = mysql_fetch_assoc($res)) {
    if(strlen($row['outTimestamp'])) {
        $row['totalHours']  = ($row['outTimestamp'] - $row['inTimestamp']);
        $monthHours[]       = $row['totalHours'];
        $monthDayCount      = $count;
    } else {
        $row['totalHours']  = (time() - $row['inTimestamp']);
        $monthHours[]       = $row['totalHours'];
        $monthDayCount      = $count;
    }
}

// year logic
$sql    = "SELECT COUNT(DISTINCT `day`, `month`, `year`) AS `count` FROM `records` WHERE ";
$sql   .= "`year` = '".mysql_real_escape_string($year)."' ";
$sql   .= "AND `day` <= '".mysql_real_escape_string( $dayPost )."' ";
$res    = mysql_query($sql) OR die(mysql_error());

$data   = mysql_fetch_assoc($res);
$count  = $data['count'];

$sql    = "SELECT * FROM `records` WHERE ";
$sql   .= "`year` = '".mysql_real_escape_string($year)."' ";
$sql   .= "AND `day` <= '".mysql_real_escape_string( $dayPost )."' ";
$res    = mysql_query($sql) OR die(mysql_error());

$data       = array();
$yearHours  = array();

while($row = mysql_fetch_assoc($res)) {
    if(strlen($row['outTimestamp'])) {
        $row['totalHours']  = ($row['outTimestamp'] - $row['inTimestamp']);
        $yearHours[]        = $row['totalHours'];
        $yearDayCount       = $count;
    } else {
        $row['totalHours']  = (time() - $row['inTimestamp']);
        $yearHours[]        = $row['totalHours'];
        $yearDayCount       = $count;
    }
}

$response->page     = 1;
$response->total    = 1;
$response->records  = 1;
$response->rows     = array(array('cell' => array(  sec2hms(array_sum($weekHours)),
                                                    $weekDayCount,
                                                    sec2hms(array_sum($monthHours)),
                                                    $monthDayCount,
                                                    sec2hms(array_sum($yearHours)),
                                                    $yearDayCount)
                                )
                            );
// return the formatted data
header('Content-Type: application/json');
echo json_encode($response);