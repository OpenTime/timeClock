<?php
/**
 * Time Clock
 * Index page
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GNU Affero General Public License v3 (AGPL-3.0)
 * @link        http://www.gnu.org/licenses/agpl-3.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Monday, February 14, 2011 / 01:54 PM GMT+1 mknox
 * @edited      $Date: 2011-03-10 12:38:09 +0100 (Thu, 10 Mar 2011) $ $Author: mknox $
 * @version     $Revision: 1 $
 *
 * @package     Time Clock
 */

define( 'THIS_PAGE', 'index' );
require_once( 'includes/config.php' );

if( !empty( $_POST ) ) {
	if( isset( $_POST['sessionUpdate'] ) ) {
		if( strlen( @$_POST['theme'] ) ) {
			$_SESSION['theme'] = $_POST['theme'];
			exit( '$_SESSION["theme"] set to:  '.$_SESSION['theme'] );
		}
	}
}

if( isset( $_REQUEST['get'] ) ) {
	switch( $_REQUEST['get'] ) {
		case 'allHours':
			exit( $smarty->display( 'html/allHours.tpl' ) );
		
		case 'settings':
			exit( $smarty->display( 'html/settings.tpl' ) );
	}	
}

$overTime 	= false;
$data 		= $timeClock->isClockedIn();
$smarty->assign('data', $data);
if( is_array( $data ) ) {
	$smarty->assign('clockedIn', true);
	$requiredHours = requiredHoursPerDay * 3600;
	$smarty->assign('requiredHours', $requiredHours);
	if( ( time() - $data[0]['inTimestamp'] ) >= $requiredHours ) {
		$overTime = true;
	}		
} else {
	$smarty->assign( 'clockedIn', false );	
}

$smarty->assign( 'overTime', $overTime );

if( @$_GET['settings'] == 'true' ) {
	exit( $jqGrid->outputSettingsJson() );
}

if( !empty( $_POST ) ) {
	if( strlen( @$_POST['clockOutAll'] ) ) {
		$timeClock->clockOutAll();
		exit;
	}
	if( !strlen( @$_POST['clockOut'] ) AND !strlen( @$_POST['clockIn'] ) ) {
		header( 'Content-Type: application/json; charset=UTF-8' );
		exit( $jqGrid->output_json() );
	} elseif( strlen( @$_POST['clockOut'] ) ) {
		$timeClock->clockOut( $_POST['Id'] );
	} elseif( strlen( @$_POST['clockIn'] ) ) {
		$timeClock->clockIn();
	}
}

if( isset( $_COOKIE['theme'] ) AND strlen( @$_COOKIE['theme'] ) ) {
	$_SESSION['theme'] = $_COOKIE['theme'];
} else {
	setcookie( 'theme', DEFAULT_JQUERY_UI_THEME );
	$_SESSION['theme'] = DEFAULT_JQUERY_UI_THEME;
}

$_SESSION['themeString'] = jQueryUIStringToTemplateName( $_SESSION['theme'] );

$smarty->display( 'html/index.tpl' );