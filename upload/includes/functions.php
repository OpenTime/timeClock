<?php
/**
 * Various Functions
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GPL v2
 * @link        http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Wednesday, October 21, 2009 / 07:01 PM UTC+1 (mknox)
 * @edited      $Date: 2011-05-03 12:36:13 +0200 (Tue, 03 May 2011) $ $Author: mknox $
 * @version     $Revision: 2 $
 *
 * @package     Time Clock
 * @subpackage  Common Functions
 */

error_reporting( E_ALL );
ini_set( 'display_errors', true );

function GetBaseDir()
{
    $pathParts 	    = pathinfo($_SERVER['SCRIPT_FILENAME']);
    $basedirPath 	= $pathParts['dirname'];
    return $basedirPath;
}

function GetBaseURL()
{
	return GetServerURL().'/'.basename( GetBaseDir() );
}

function WriteLog($filename, $msg, $mode = 'a')
{
    $fd = fopen($filename, $mode);
    fwrite($fd, "[" .date('l, F j, Y / h:i:s A T (\G\M\TO)'). "]\n");
	fwrite($fd, $msg. "\n");
	fclose($fd);
}

function LogtoDB($userid, $event, $log_type, $filename, $source, $txt, $interactive = 0)
{
    global $db;

    $sql    = "INSERT INTO logs (date,userid,event,log_type,filename,source,txt,useragent,ip) ";
    $sql   .= "VALUES ('".time()."','".$userid."','".$event."','".$log_type."','".$filename."',";
    $sql   .= "'".mysql_real_escape_string($source)."','".mysql_real_escape_string($txt)."',";
    $sql   .= "'".$_SERVER['HTTP_USER_AGENT']."','".$_SERVER['REMOTE_ADDR']."')";

    if ($db->Execute($sql) === false) {
        if($interactive == 1) {
            exit('Database insert error: '.__FILE__.', Line: '.__LINE__.': '.$db->ErrorMsg().'<BR>');
        } else {
            WriteLog(BASEDIR.'/logs/db/db_error.log',
            'Database insert error: '.__FILE__.', Line: '.__LINE__.': '.$db->ErrorMsg().'\n');
        }
    }
}

function LogAccess($userid = 0)
{
    global $db;
    $userid = (!empty($_SESSION['userid'])) ? $_SESSION['userid'] : $userid;

    $txt  = "IP Address:  ".$_SERVER['REMOTE_ADDR']."\n";
    $txt .= "Request Method:  ".$_SERVER['REQUEST_METHOD']."\n";
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $txt .= "\$_POST:  ".var_export($_POST, true);
    } else {
        $txt .= "\$_GET:  ".var_export($_REQUEST, true);
    }

    if(!empty($_FILES)) {
        $txt .= "\n\$_FILES:  ".var_export($_FILES, true)."\n";
    }

    if(!empty($_SESSION)) {
        $txt .= "\n\$_SESSION:  ".var_export($_SESSION, true)."\n";
    }

    $sql    = "INSERT INTO logs_access (date,userid,page,request,useragent,ip) ";
    $sql   .= "VALUES ('".time()."','".$userid."','".curPageURL()."','".mysql_real_escape_string($txt)."',";
    $sql   .= "'".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','".$_SERVER['REMOTE_ADDR']."')";

    if ($db->Execute($sql) === false) {
        if($interactive == 1) {
        exit('Database insert error: '.__FILE__.', Line: '.__LINE__.': '.$db->ErrorMsg().'<BR>');
        } else {
            WriteLog(BASEDIR.'/logs/db/db_error.log',
            'Database insert error: '.__FILE__.', Line: '.__LINE__.': '.$db->ErrorMsg().'\n');
        }
    }
}

function GetServerURL()
{
    return GetServerProtocol().$_SERVER['HTTP_HOST'];
}

function curPageURL()
{
    $pageURL = 'http';

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
        $pageURL .= "s";
    }

    $pageURL       .= "://";
    $pageURL       .= $_SERVER['SERVER_NAME'];
    $pageURL       .= $_SERVER['PHP_SELF'];
    $queryString    = $_SERVER['QUERY_STRING'];

    if(strlen($queryString)){
        $pageURL .= '?'.$queryString;
    }

    return $pageURL;
}

function GetServerProtocol()
{
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        return 'https://';
    } else {
        $protocol = preg_replace('/^([a-z]+)\/.*$/', '\\1', strtolower($_SERVER['SERVER_PROTOCOL']));
        $protocol .= '://';

        return $protocol;
    }
}


/*
 * @link http://www.laughing-buddha.net/php/lib/sec2hms
 */
function sec2hms ($sec, $padHours = true)
{
    // start with a blank string
    $hms = "";

    // do the hours first: there are 3600 seconds in an hour, so if we divide
    // the total number of seconds by 3600 and throw away the remainder, we're
    // left with the number of hours in those seconds
    $hours = intval(intval($sec) / 3600);

    // add hours to $hms (with a leading 0 if asked for)
    $hms .= ($padHours)
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
          : $hours. ":";

    // dividing the total seconds by 60 will give us the number of minutes
    // in total, but we're interested in *minutes past the hour* and to get
    // this, we have to divide by 60 again and then use the remainder
    $minutes = intval(($sec / 60) % 60);

    // add minutes to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

    // seconds past the minute are found by dividing the total number of seconds
    // by 60 and using the remainder
    $seconds = intval($sec % 60);

    // add seconds to $hms (with a leading 0 if needed)
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;
}

function timestampToHours($timestamp)
{
    if(!strlen($timestamp)) {
        return;
    }

    $hours = intval(intval($timestamp) / 3600);

    return $hours;
}

function timestampToMinutes($timestamp)
{
    if(!strlen($timestamp)) {
        return;
    }

    $minutes = intval(($timestamp / 60));

    return $minutes;
}

function timestampToSeconds($timestamp)
{
    if(!strlen($timestamp)) {
        return;
    }

    $seconds = intval($timestamp % 60);

    return $seconds;
}

function hoursToMinutes($hours)
{
    return $hours * 60;
}

function minutesToHours($minutes)
{
    return number_format($minutes / 60);
}

function minutesToHoursAndMinutes($minutes)
{
    if($minutes <= 59) {
        return '00:'.str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    $result = $minutes / 60;

    preg_match('/.(\d{2})/', $result, $matches);
    if(!empty($matches)) {
        $percent = number_format($matches[0], 2);
        $minutes = str_pad(round($percent * 60), 2, '0', STR_PAD_LEFT);
    } else {
        if(preg_match('/.(\d{1})/', $result, $matches)) {
            $percent = number_format($matches[0], 2);
            $minutes = str_pad(round($percent * 60), 2, '0', STR_PAD_LEFT);
        } else {
            $minutes = '00';
        }
    }

    $hours = number_format($result);
    return $hours.':'.$minutes;
}

function minutesOverAnHour($minutes)
{
    return number_format(($minutes / 60) * 10);
}

/**
 * return day count minus weekends
 *
 * @param   week    week number
*/
function daysMinusWeekends($week)
{
    return $week * 5;
}

/**
 * find the first and last day of the month from the given date.
 *
 * @author  Binu v Pillai <binupillai2003@yahoo.com>
 * @link    http://www.php.net/manual/en/function.getdate.php#93130
 * @param   string  $anyDate format should be yyyy-mm-dd
 * @return  array
 */
function findFirstAndLastDay($anyDate)
{
    list($yr,$mn,$dt)   = split('-',$anyDate);    // separate year, month and date
    $timeStamp          = mktime(0,0,0,$mn,1,$yr);    //Create time stamp of the first day from the give date.
    $firstDay           = date('Y-m-d',$timeStamp);    //get first day of the given month
    list($y,$m,$t)      = split('-',date('Y-m-t',$timeStamp)); //Find the last date of the month and separating it
    $lastDayTimeStamp   = mktime(0,0,0,$m,$t,$y);//create time stamp of the last date of the give month
    $lastDay            = date('Y-m-d', $lastDayTimeStamp);// Find last day of the month
    $arrDay             = array('firstDay' => $firstDay, 'lastDay' => $lastDay); // return the result in an array format.

    return $arrDay;
}

/**
 * function/method to calculate the next workday, taking into account US federal holidays
 *
 * @author  <moshe@unirgy.com>
 * @link    http://www.php.net/manual/en/function.getdate.php#93395
 * @param   int
 * @return  int
 */
function getNextWorkDayTime($date=null)
{
    $time = is_string($date) ? strtotime($date) : (is_int($date) ? $date : time());
    $time = $time + 86400;
    $y = date('Y', $time);
    // calculate federal holidays
    $holidays = array();
    // month/day (jan 1st). iteration/wday/month (3rd monday in january)
    $hdata = array('1/1'/*newyr*/, '7/4'/*jul4*/, '11/11'/*vet*/, '12/25'/*xmas*/, '3/1/1'/*mlk*/, '3/1/2'/*pres*/, '5/1/5'/*memo*/, '1/1/9'/*labor*/, '2/1/10'/*col*/, '4/4/11'/*thanks*/);
    foreach ($hdata as $h1) {
        $h = explode('/', $h1);
        if (sizeof($h)==2) { // by date
            $htime = mktime(0, 0, 0, $h[0], $h[1], $y); // time of holiday
            $w = date('w', $htime); // get weekday of holiday
            $htime += $w==0 ? 86400 : ($w==6 ? -86400 : 0); // if weekend, adjust
        } else { // by weekday
            $htime = mktime(0, 0, 0, $h[2], 1, $y); // get 1st day of month
            $w = date('w', $htime); // weekday of first day of month
            $d = 1+($h[1]-$w+7)%7; // get to the 1st weekday
            for ($t=$htime, $i=1; $i<=$h[0]; $i++, $d+=7) { // iterate to nth weekday
                 $t = mktime(0, 0, 0, $h[2], $d, $y); // get next weekday
                 if (date('n', $t)>$h[2]) break; // check that it's still in the same month
                 $htime = $t; // valid
            }
        }
        $holidays[] = $htime; // save the holiday
    }
    for ($i=0; $i<5; $i++, $time+=86400) { // 5 days should be enough to get to workday
        if (in_array(date('w', $time), array(0, 6))) continue; // skip weekends
        foreach ($holidays as $h) { // iterate through holidays
            if ($time>=$h && $time<$h+86400) continue 2; // skip holidays
        }
        break; // found the workday
    }
    return $time;
}

/**
 * will echo all saturdays found between date range.
 *
 * @author  <india.yogi@gmail.com>
 * @link    http://www.php.net/manual/en/function.date.php#102293
 * @param   string
 * @param   string
 * @return  int
 */
function getAllSaturdays($from_date, $to_date)
{
    // getting number of days between two date range.
    $number_of_days = count_days(strtotime($from_date),strtotime($to_date));
    $saturdays      = array();
    $count          = 0;

    for($i = 1; $i <= $number_of_days; $i++){
        $day = Date('l',mktime(0,0,0,date('m'),date('d')+$i,date('y')));
        if($day == 'Saturday') {
            $count++;
            $saturdays['text'][]    = Date('d-m-Y',mktime(0,0,0,date('m'),date('d')+$i,date('y')));
            $saturdays['count']     = $count;
        }
    }

    if(!empty($saturdays)) {
        return $saturdays;
    }
}

/**
 * will echo all sundays found between date range.
 *
 * @author  <india.yogi@gmail.com>
 * @link    http://www.php.net/manual/en/function.date.php#102293
 * @param   string
 * @param   string
 * @return  int
 */
function getAllSundays($from_date, $to_date)
{
    // getting number of days between two date range.
    $number_of_days = count_days(strtotime($from_date),strtotime($to_date));
    $sundays        = array();
    $count          = 0;

    for($i = 1; $i <= $number_of_days; $i++){
        $day = Date('l',mktime(0,0,0,date('m'),date('d')+$i,date('y')));
        if($day == 'Sunday'){
            $count++;
            $sundays['text'][]    = Date('d-m-Y',mktime(0,0,0,date('m'),date('d')+$i,date('y')));
            $sundays['count']     = $count;
        }
    }

    if(!empty($sundays)) {
        return $sundays;
    }
}

/**
 * will return the number of days between the two dates passed in
 *
 * @author  <india.yogi@gmail.com>
 * @link    http://www.php.net/manual/en/function.date.php#102293
 * @example // todays date
 *          $from_date = Date('d-m-Y');
 *          // date with one year difference i.e. same date of next year
 *          $to_date = Date('d-m-Y',mktime(0,0,0,date('m'),date('d'),date('y')+1));
 *
 * @param   int
 * @param   int
 * @return  int
 */
function count_days( $a, $b )
{
    // First we need to break these dates into their constituent parts:
    $gd_a = getdate( $a );
    $gd_b = getdate( $b );
    // Now recreate these timestamps, based upon noon on each day
    // The specific time doesn't matter but it must be the same each day
    $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
    $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
    // Subtract these two numbers and divide by the number of seconds in a
    // day. Round the result since crossing over a daylight savings time
    // barrier will cause this time to be off by an hour or two.
    return round( abs( $a_new - $b_new ) / 86400 );
}

/**
 * Most spreadsheet programs have a rather nice little built-in function called
 * NETWORKDAYS to calculate the number of business days (i.e. Monday-Friday,
 * excluding holidays) between any two given dates. I couldn't find a simple
 * way to do that in PHP, so I threw this together. It replicates the
 * functionality of OpenOffice's NETWORKDAYS function - you give it a
 * start date, an end date, and an array of any holidays you want skipped, and
 * it'll tell you the number of business days (inclusive of the start and
 * end days!) between them.
 * I've tested it pretty strenuously but date arithmetic is complicated and
 * there's always the possibility I missed something, so please feel free
 * to check my math.
 *
 * The function could certainly be made much more powerful, to allow you to
 * set different days to be ignored (e.g. "skip all Fridays and Saturdays but
 * include Sundays") or to set up dates that should always be skipped (e.g.
 * "skip July 4th in any year, skip the first Monday in September in any year").
 * But that's a project for another time.
 *
 * @author  ghotinet
 * @link    http://www.php.net/manual/en/function.date.php#101379
 * @example $start = strtotime("1 January 2010");
 *          $end = strtotime("13 December 2010");
 *
 *          // Add as many holidays as desired.
 *          $holidays = array();
 *          // Falls on a Sunday; doesn't affect count
 *          $holidays[] = "4 July 2010";
 *          // Falls on a Monday; reduces count by one
 *          $holidays[] = "6 September 2010";
 *          // Returns 246
 *          echo networkdays($start, $end, $holidays);
 *
 * @param   int
 * @param   int
 * @param   array
 * @return  int
 */
function networkdays($s, $e, $holidays = array())
{
    // If the start and end dates are given in the wrong order, flip them.
    if ($s > $e)
        return networkdays($e, $s, $holidays);

    // Find the ISO-8601 day of the week for the two dates.
    $sd = date("N", $s);
    $ed = date("N", $e);

    // Find the number of weeks between the dates.
    $w = floor(($e - $s)/(86400*7));    # Divide the difference in the two times by seven days to get the number of weeks.
    if ($ed >= $sd) { $w--; }        # If the end date falls on the same day of the week or a later day of the week than the start date, subtract a week.

    // Calculate net working days.
    $nwd = max(6 - $sd, 0);    # If the start day is Saturday or Sunday, add zero, otherewise add six minus the weekday number.
    $nwd += min($ed, 5);    # If the end day is Saturday or Sunday, add five, otherwise add the weekday number.
    $nwd += $w * 5;        # Add five days for each week in between.

    // Iterate through the array of holidays. For each holiday between the start and end dates that isn't a Saturday or a Sunday, remove one day.
    foreach ($holidays as $h) {
        $h = strtotime($h);
        if ($h > $s && $h < $e && date("N", $h) < 6)
            $nwd--;
    }

    return $nwd;
}

/**
 * how many work days there are in any given year
 *
 * @author  ghotinet
 * @link    http://www.php.net/manual/en/function.date.php#101379
 *
 * @param   int
 * @return  int
 */
function workdaysinyear($y)
{
    $j1 = mktime(0,0,0,1,1,$y);
    if (date("L", $j1)) {
        if (date("N", $j1) == 6)
            return 260;
        elseif (date("N", $j1) == 5 or date("N", $j1) == 7)
            return 261;
        else
            return 262;
    }
    else {
        if (date("N", $j1) == 6 or date("N", $j1) == 7)
            return 260;
        else
            return 261;
    }
}

/**
 * Here is a simple function that gets all the dates between
 * 2 given dates and returns an array (including the dates specified)
 *
 * @author  imran@t555.net
 * @link    http://www.php.net/manual/en/function.date.php#100251
 * @example $dates_array = dates_inbetween('2001-12-28', '2002-01-01');
 *
 * @param   int
 * @return  int
 */
function dates_inbetween($date1, $date2)
{
    $day = 60*60*24;

    $date1 = strtotime($date1);
    $date2 = strtotime($date2);

    $days_diff = round(($date2 - $date1)/$day); // Unix time difference devided by 1 day to get total days in between

    $dates_array = array();

    $dates_array[] = date('Y-m-d',$date1);

    for($x = 1; $x < $days_diff; $x++){
        $dates_array[] = date('Y-m-d',($date1+($day*$x)));
    }

    $dates_array[] = date('Y-m-d',$date2);

    return $dates_array;
}

/**
 * Finds the difference in days between two calendar dates.
 *
 * @author  phprocks at aol dot com
 * @link    http://www.php.net/manual/en/function.date.php#84942
 * @param   date $startDate
 * @param   date $endDate
 * @return  int
 */
function dateDiff($startDate, $endDate)
{
    // Parse dates for conversion
    $startArry = date_parse($startDate);
    $endArry = date_parse($endDate);

    // Convert dates to Julian Days
    $start_date = gregoriantojd($startArry["month"], $startArry["day"], $startArry["year"]);
    $end_date = gregoriantojd($endArry["month"], $endArry["day"], $endArry["year"]);

    // Return difference
    return round(($end_date - $start_date), 0);
}

/**
 * number of days between 2 dates
 *
 * @author  michiel at mb-it dot nl
 * @link    http://www.php.net/manual/en/function.date.php#69387
 * @example // we set today as an example
 *          $begin = date("Y/m/d");
 *          $end = "2006/11/27";
 *          getdays($begin,$end);
 * @param   string
 * @param   string
 * @return  int
 */
function getdays($day1,$day2)
{
    return round((strtotime($day2)-strtotime($day1))/(24*60*60),0);
}

/**
 * This function is similar to getdate() but it returns
 * the month information.
 *
 * Returns an associative array containing the month
 * information of the parameters, or the current month
 * if no parameters are given.
 *
 * @author  cesar at nixar dot org
 * @link    http://www.php.net/manual/en/function.getdate.php#70633
 * @example // Output
 *          print_r(getmonth(11, 1978));
 *          print_r(getmonth());
 */
function getmonth ($month = null, $year = null)
{
      // The current month is used if none is supplied.
      if (is_null($month))
          $month = date('n');

      // The current year is used if none is supplied.
      if (is_null($year))
          $year = date('Y');

      // Verifying if the month exist
      if (!checkdate($month, 1, $year))
          return null;

      // Calculating the days of the month
      $first_of_month = mktime(0, 0, 0, $month, 1, $year);
      $days_in_month = date('t', $first_of_month);
      $last_of_month = mktime(0, 0, 0, $month, $days_in_month, $year);

      $m = array();
      $m['first_mday'] = 1;
      $m['first_wday'] = date('w', $first_of_month);
      $m['first_weekday'] = strftime('%A', $first_of_month);
      $m['first_yday'] = date('z', $first_of_month);
      $m['first_week'] = date('W', $first_of_month);
      $m['last_mday'] = $days_in_month;
      $m['last_wday'] = date('w', $last_of_month);
      $m['last_weekday'] = strftime('%A', $last_of_month);
      $m['last_yday'] = date('z', $last_of_month);
      $m['last_week'] = date('W', $last_of_month);
      $m['mon'] = $month;
      $m['month'] = strftime('%B', $first_of_month);
      $m['year'] = $year;

      return $m;
}

/**
 * First Day Of Month
 *
 * Here is an often requested function to fetch the first day of the month.
 * The function takes a single, optional parameter which is a unix timestamp
 * of any date. The function will then return the unix timestamp of the first
 * day of the month from the UNIX TIMESTAMP. If no timestamp is given,
 * the function defaults to the current month.
 *
 * @link    http://www.phpro.org/examples/First-Day-Of-Month.html
 * @return  array
 * @param INT Unix timestamp
 *
 * @return array
 */
function firstDayOfMonth($uts=null)
{
    $today                  = is_null($uts) ? getDate() : getDate($uts);
    $firstDay               = getdate(mktime(0,0,0,$today['mon'],1,$today['year']));
    $firstDay['timestamp']  = $firstDay[0];
    $firstDay['formatted']  = $firstDay[0];

    return $firstDay;
}

/**
 * first day of the month
 *
 * @link   http://lutrov.com/blog/php-last-day-of-the-month-calculation
 * @link   http://www.justin-cook.com/wp/2009/04/18/get-the-first-last-day-of-the-month-with-php/#comment-157055
 */
function firstOfMonth($month = '', $year = '')
{
    if(!strlen($month)) {
        $month = date('m');
    }

    if(!strlen($year)) {
        $year = date('Y');
    }

    $result = strtotime("{$year}-{$month}-01");
    return date('Y-m-d', $result);
}

/**
 * last day of the month
 *
 * @link   http://lutrov.com/blog/php-last-day-of-the-month-calculation
 * @link   http://www.justin-cook.com/wp/2009/04/18/get-the-first-last-day-of-the-month-with-php/#comment-157055
 */
function lastOfMonth($month = '', $year = '')
{
    if(!strlen($month)) {
        $month = date('m');
    }

    if(!strlen($year)) {
        $year = date('Y');
    }

    $result = strtotime("{$year}-{$month}-01");
    $result = strtotime('-1 second', strtotime('+1 month', $result));

    return date('Y-m-d', $result);
}
/**
 * Calculate Days In A Month
 *
 * One line of logic provides the number of days in a month, taking
 * into account leap years, and all on one line.
 *
 * PHP does offer a cal_days_in_month function for PHP builds of PHP 4.0.7
 * and higher, but I prefer using the below function because it is
 * guaranteed to work on all PHP version because it is based solely on logic.
 *
 * @link    http://davidwalsh.name/php-function-calculating-days-in-a-month
 */
function get_days_in_month($month = '', $year = '')
{
    $month  = (!strlen($month)) ? date('m') : $month;
    $year   = (!strlen($year)) ? date('Y') : $year;

    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year %400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}

/**
 * will echo all saturdays found between date range.
 *
 * @param   string
 * @param   string
 * @return  int
 */
function countWeekendDays($from, $to)
{
    $sat = getAllSaturdays($from, $to);
    $sun = getAllSundays($from, $to);

    return ($sat['count'] + $sun['count']);
}

function checkInstall()
{
	if( !file_exists(BASEDIR.'/includes/timeClock.installed') ) {
		exit('not installed');	
	}	
}

function jQueryUIStringToTemplateName( $string )
{
	$string = str_replace( ' ', '-', $string );
	$string = strtolower( $string );

	return $string;
}