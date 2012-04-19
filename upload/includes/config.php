<?php
/**
 * Time Clock
 * Global Config
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GPL v2
 * @link        http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Wednesday, October 21, 2009 / 06:52 PM GMT+1 mknox
 * @edited      $Date: 2011-05-09 08:14:43 +0200 (Mon, 09 May 2011) $ $Author: mknox $
 * @version     $Revision: 4 $
 *
 * @package     Time Clock
 */

error_reporting(E_ALL);
ini_set('display_errors', true);
define('IN_SITE',true);
// 10 years
define('COOKIE_TIMEOUT',315360000);
define('GARBAGE_TIMEOUT',COOKIE_TIMEOUT);
ini_set('session.gc_maxlifetime', GARBAGE_TIMEOUT);
session_set_cookie_params(COOKIE_TIMEOUT,'/');
// setting session dir
$sessdir = '/tmp/'.$_SERVER['HTTP_HOST'];
// if session dir not exists, create directory
if (!is_dir($sessdir)) {
    @mkdir($sessdir, 0777);
}
// if directory exists, then set session.savepath otherwise let it go as is
if(is_dir($sessdir)) {
    ini_set('session.save_path', $sessdir);
}
session_start();

require_once('db.php');
require_once('functions.php');
require_once('constants.php');
require_once('classes/singleton.class.php');
require_once('classes/PHPMailer/class.phpmailer.php');
require_once('classes/timeClock.class.php');
require_once('classes/jqGrid.class.php');
require_once('classes/Smarty/Smarty.class.php');
$mail                   = Singleton::getInstance('PHPMailer');
$jqGrid                 = Singleton::getInstance('jqGrid');
$smarty                 = Singleton::getInstance('Smarty');
$smarty->compile_check  = true;
$smarty->debugging      = false;
$timeClock              = Singleton::getInstance('timeClock');
$timeClock->defineConfig();
