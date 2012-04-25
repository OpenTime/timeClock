<?php
/**
 * Time Clock
 * Constants
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GPL v2
 * @link        http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Thursday, October 22, 2009 / 12:05 PM GMT+1 mknox
 * @edited      $Date: 2011-05-09 08:14:43 +0200 (Mon, 09 May 2011) $ $Author: mknox $
 * @version     $Revision: 4 $
 *
 * @package     Time Clock
 */

define( 'BASEDIR', GetBaseDir() );
define( 'BASEURL', GetBaseURL() );
define( 'TEMPLATEDIR', BASEDIR.'/templates/' );
define( 'TEMPLATENAME', 'generic' );
define( 'TEMPLATE', TEMPLATEDIR.TEMPLATENAME );
define( 'TEMPLATE_ROOT', BASEURL.'/templates/'.TEMPLATENAME );
define( 'TEMPLATE_CSS', BASEURL.'/templates/'.TEMPLATENAME.'/css' );
define( 'TEMPLATE_IMG', BASEURL.'/templates/'.TEMPLATENAME.'/images' );
define( 'TEMPLATE_JS', BASEURL.'/templates/'.TEMPLATENAME.'/js' );
define( 'THIS_URL', curPageURL() );
define( 'CURRENT_SCRIPT', ltrim( $_SERVER['SCRIPT_NAME'], '/' ) );
define( 'DEFAULT_JQUERY_UI_THEME', 'Redmond' );