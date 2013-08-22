<?php
/**
 * Time Clock
 * Installer
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GNU Affero General Public License v3 (AGPL-3.0)
 * @link        http://www.gnu.org/licenses/agpl-3.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Tuesday, August 20, 2013 / 23:31 GMT+1 mknox
 * @edited      $Date: 2011-03-10 12:38:09 +0100 (Thu, 10 Mar 2011) $ $Author: mknox $
 * @version     $Revision: 1 $
 *
 * @package     Time Clock
*/

define( 'IN_INSTALL', true );
require_once( '../includes/config.php' );

$errors = array();
if( !is_writable( $smarty->cache_dir ) ) {
	$errors[] = $smarty->cache_dir;
	exit( 'Please CHMOD '.$smarty->cache_dir.' to 0777' );	
}

$smarty->display( 'html/install/index.tpl' );