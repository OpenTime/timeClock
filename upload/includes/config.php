<?php
/**
 * Time Clock
 * Global Config
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GNU Affero General Public License v3 (AGPL-3.0)
 * @link        http://www.gnu.org/licenses/agpl-3.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Wednesday, October 21, 2009 / 06:52 PM GMT+1 mknox
 * @edited      $Date: 2011-05-09 08:14:43 +0200 (Mon, 09 May 2011) $ $Author: mknox $
 * @version     $Revision: 4 $
 *
 * @package     Time Clock
 */

error_reporting( E_ALL );
ini_set( 'display_errors', true );
define( 'IN_SITE',true );
// 10 years
define( 'COOKIE_TIMEOUT', 315360000 );
define( 'GARBAGE_TIMEOUT', COOKIE_TIMEOUT );
ini_set( 'session.gc_maxlifetime', GARBAGE_TIMEOUT );
session_set_cookie_params( COOKIE_TIMEOUT, '/' );
// setting session dir
if( !defined( 'IN_PHPUNIT' ) ) {
	$sessdir = '/tmp/'.$_SERVER['HTTP_HOST'];
	// if session dir not exists, create directory
	if ( !is_dir( $sessdir ) ) {
		@mkdir( $sessdir, 0777 );
	}

	// if directory exists, then set session.savepath otherwise let it go as is
	if( is_dir( $sessdir ) ) {
		ini_set( 'session.save_path', $sessdir );
	}
	session_start();	
}

require_once('functions.php');
require_once('constants.php');

if( isset( $_COOKIE['theme'] ) AND strlen( @$_COOKIE['theme'] ) ) {
	$_SESSION['theme'] = $_COOKIE['theme'];
} else {
	setcookie( 'theme', DEFAULT_JQUERY_UI_THEME );
	$_SESSION['theme'] = DEFAULT_JQUERY_UI_THEME;
}

$_SESSION['themeString'] = jQueryUIStringToTemplateName( $_SESSION['theme'] );

if( file_exists( BASEDIR.'/install.me' ) && !defined('IN_INSTALL') ) {
	header( 'Location:'.BASEURL.'/install' );
}

require_once('classes/singleton.class.php');
require_once('classes/Smarty/Smarty.class.php');

$smarty                 = Singleton::getInstance('Smarty');
$smarty->compile_check  = true;
$smarty->cache_dir		= SMARTY_CACHE_DIR;
$smarty->compile_dir	= SMARTY_COMPILE_DIR;
$smarty->debugging      = false;
$smarty->template_dir 	= SMARTY_TEMPLATE_DIR;
$smarty->loadPlugin('smarty_compiler_switch');

if( !defined( 'IN_INSTALL') ) {
	require_once('db.php');	
}

require_once('classes/PHPMailer/class.phpmailer.php');

if( !defined( 'IN_INSTALL') ) {
	require_once('classes/timeClock.class.php');
	require_once('classes/jqGrid.class.php');
}

$mail = Singleton::getInstance('PHPMailer');

if( !defined( 'IN_INSTALL') ) {
	$jqGrid		= Singleton::getInstance('jqGrid');
	$timeClock	= Singleton::getInstance('timeClock');
	$timeClock->defineConfig();
}