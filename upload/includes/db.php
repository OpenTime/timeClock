<?php
/**
 * Time Clock
 * DB Connection
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GPL v2
 * @link        http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Saturday, September 19, 2009 / 10:50 PM GMT+1 mknox
 * @edited      $Date: 2011-03-10 12:38:09 +0100 (Thu, 10 Mar 2011) $ $Author: mknox $
 * @version     $Revision: 1 $
 *
 * @package     Time Clock
 */

// database host
$config['db_host']  = 'localhost';
// database name
$config['db_name']  = 'time_clock';
// database username
$config['db_user']  = 'root';
// database password
$config['db_pass']  = '';

if ( !function_exists('mysql_set_charset') ) {
     /**
      * Sets the client character set.
      *
      * Note: This function requires MySQL 5.0.7 or later.
      *
      * @see http://www.php.net/mysql-set-charset
      * @param string $charset A valid character set name
      * @param resource $link_identifier The MySQL connection
      * @return TRUE on success or FALSE on failure
      */
     function mysql_set_charset($charset, $link_identifier = null)
     {
         if ($link_identifier == null) {
             return mysql_query('SET NAMES "'.$charset.'"');
         } else {
             return mysql_query('SET NAMES "'.$charset.'"', $link_identifier);
         }
     }
}

$mysql = mysql_connect($config['db_host'], $config['db_user'], $config['db_pass']) OR die(mysql_error());
mysql_select_db($config['db_name']) OR die(mysql_error());
mysql_set_charset('utf8', $mysql);
mysql_query( 'SET NAMES utf8', $mysql );