<?php
/**
 * Time Clock
 * Index page
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GPL v2
 * @link        http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Monday, February 14, 2011 / 01:54 PM GMT+1 mknox
 * @edited      $Date: 2011-03-10 12:38:09 +0100 (Thu, 10 Mar 2011) $ $Author: mknox $
 * @version     $Revision: 1 $
 *
 * @package     Time Clock
 */

define('THIS_PAGE', 'index');
require_once('includes/config.php');

$data = $timeClock->isClockedIn();
if( is_array( $data ) ) {
    $clockedIn = true;
}

if(@$_GET['settings'] == 'true') {
    exit($jqGrid->outputSettingsJson());
}

if(!empty($_POST)) {
    if(!strlen(@$_POST['clockOut']) AND !strlen(@$_POST['clockIn'])) {
        header('Content-Type: application/json; charset=UTF-8');
        exit($jqGrid->output_json());
    } elseif(strlen(@$_POST['clockOut'])) {
        $timeClock->clockOut($_POST['Id']);
    } elseif(strlen(@$_POST['clockIn'])) {
        $timeClock->clockIn();
    }
}

if( !isset( $_SESSION['jquery-ui-theme'] ) OR !strlen( @$_SESSION['jquery-ui-theme'] ) ) {
	setcookie( 'jquery-ui-theme', 'Redmond' );
	$_SESSION['jquery-ui-theme'] = 'Redmond';	
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Time Clock</title>
	<link rel="stylesheet" href="css/jquery-ui/ui-blank-white/jquery-ui-1.8.19.custom.css">
	<script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/jquery-ui-1.8.19.custom.min.js"></script>
    <!--link rel="stylesheet" type="text/css" href="css/jquery.countdown.css"-->
    <script type="text/javascript" src="js/jquery.countdown.pack.js"></script>

    <link rel="stylesheet" type="text/css" media="screen" href="css/jqGrid/ui.jqgrid.css" />
    <script src="js/jqGrid/grid.locale-en.js" type="text/javascript"></script>
    <script src="js/jqGrid/jquery.jqGrid.min.js" type="text/javascript"></script>
    
    <!--  blockUI -->
    <script src="js/jquery.blockUI.js" type="text/javascript"></script>
    
    <!-- php.js -->
	<script src="js/phpjs.js" type="text/javascript"></script>
    
    <!-- jQuery UI Themeswitcher -->
	<script src="http://jqueryui.com/themeroller/themeswitchertool/" type="text/javascript"></script>    
    
	<script type="text/javascript">
		var CURRENT_THEME = '<?php echo $_SESSION['jquery-ui-theme']; ?>';
		if( strlen( CURRENT_THEME ) ) {
		} else {
			CURRENT_THEME = 'Redmond';
		}	
			
		$(document).ready(function() {			
	    	$('#switcher').themeswitcher( { loadTheme: CURRENT_THEME, cookieName: 'jquery-ui-theme', 
		    	onSelect: function() {
		    		
	        	}
	    	});	    	
		});			
	</script>
    
    <?php if( @$clockedIn ) {
    ?>
	<script type="text/javascript">
	$(function() {
		$('#dialog-message').dialog({
		    width: 460,
			modal: true,
			buttons: {
				'Clock Out': function() {
                    $.post('index.php', { clockOut: 'true', Id: <?php echo $data[0]['Id']; ?>},
                        function(data) {
                            if(data == 'OK') {
                                $('#list').trigger('reloadGrid');
					            $('#dialog-message').dialog('close');
                            }
                    });
				}
			}
		});
	});
	</script>
    <?php
    } else {
    ?>
	<script type="text/javascript">
	$(function() {
		$('#dialog-message').dialog({
		    width: 460,
			modal: true,
			buttons: {
				'Clock In': function() {
                    $.post('index.php', { clockIn: 'true'},
                        function(data) {
                            if(data == 'OK') {
                                $('#list').trigger('reloadGrid');
					            $('#dialog-message').dialog('close');
                            }
                    });
				}
			}
		});
	});
	</script>
    <?php
    }
    ?>
</head>
<body style="display: none;">
    <p>
    	<div id="switcher"></div>
    </p>
    
	<div>

<?php if(@$clockedIn) {
?>
<div id="dialog-message" title="Clocked in">
	<p>
    You are currently clocked in.
    <br />
    Clock in time:&nbsp;&nbsp;<?php echo date('l, F d, Y H:i:s', $data[0]['inTimestamp']) ?>
    </p>
    <div id="sinceCountdown" style="white-space: nowrap"></div>
    <script type="text/javascript">
        $('#sinceCountdown').countdown({
            since: new Date('<?php echo date('F d, Y H:i:s', $data[0]['inTimestamp']) ?>'),
            format: 'HMS',
            layout: 'Clocked in for:&nbsp;&nbsp;{hn} {hl} {mn} {ml} {sn} {sl}',
        });
    </script>
</div>

<div id="logout-message" title="Clocking out..." style="display: none;">
    <p>Clocking out. Please wait...</p>
</div>
<?php
} else {
?>
<div id="dialog-message" title="Clocked in">
	<p>You are not currently clocked in.</p>
</div>
<?php
}
?>
</div>

	<script type="text/javascript">
		$(function() {          
			$( "#tabs" ).tabs({
				ajaxOptions: {
					error: function( xhr, status, index, anchor ) {
						$( anchor.hash ).html(
							"Couldn't load this tab. We'll try to fix this as soon as possible. " +
							"If this wouldn't be a demo." );
					}
				}
			});

    		$.unblockUI();
    		$('body').show();			
		});
	</script>

<div id="mainBody" class="ui-widget-content">	
	<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
				<a href="all_hours.html">All Hours</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="settings.html">Settings</a>
			</li>
		</ul>
	</div>

</div>

<style type="text/css">
	html {font-size:70%}
	body {font-size:100%}
</style>

<!--  START:	blockUI on page load -->
<div style="display: none;" class="blockUI"></div>
<div style="z-index: 1000; border: medium none; margin: 0pt; padding: 0pt; width: 100%; height: 100%; top: 0pt; left: 0pt; background-color: rgb(255, 255, 255); opacity: 100; cursor: wait; position: fixed;" class="blockUI blockOverlay"></div>
<div style="z-index: 1011; display: none; position: fixed;" class="blockUI blockMsg blockPage"></div>
<!--  END:		blockUI on page load -->

</body>
</html>