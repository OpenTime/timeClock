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

define( 'THIS_PAGE', 'index' );
require_once('includes/config.php');

if( !empty( $_POST ) ) {
	if( isset( $_POST['sessionUpdate'] ) ) {
		if( strlen( @$_POST['theme'] ) ) {
			$_SESSION['theme'] = $_POST['theme'];
			exit( '$_SESSION["theme"] set to:  '.$_SESSION['theme'] );
		}
	}
}

$data = $timeClock->isClockedIn();
if( is_array( $data ) ) {
    $clockedIn = true;
}

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Time Clock</title>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/<?php echo $_SESSION['themeString']; ?>/jquery-ui.css">
	
	<script type="text/javascript">
		var DEBUG = true;
	</script>
	
	<script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/jquery-ui-1.8.19.custom.min.js"></script>        
    <script src="js/consolelog.min.js"></script>
    <!--link rel="stylesheet" type="text/css" href="css/jquery.countdown.css"-->
    <script type="text/javascript" src="js/jquery.countdown.pack.js"></script>

    <link rel="stylesheet" type="text/css" media="screen" href="css/jqGrid/ui.jqgrid.css" />
    <script src="js/jqGrid/grid.locale-en.js" type="text/javascript"></script>
    <script src="js/jqGrid/jquery.jqGrid.min.js" type="text/javascript"></script>
    
    <!--  blockUI -->
    <script src="js/jquery.blockUI.js" type="text/javascript"></script>
    
    <!--  custom functions -->
    <script src="js/functions.js?<?php echo rand(); ?>"></script>
    
    <!-- php.js -->
	<script src="js/phpjs.js?<?php echo rand(); ?>" type="text/javascript"></script>
    
    <!-- jQuery UI Themeswitcher -->
	<!-- script src="http://jqueryui.com/themeroller/themeswitchertool/" type="text/javascript"></script-->	        
	<script src="js/themeswitcher.js?<?php echo rand(); ?>" type="text/javascript"></script>
    
	<script type="text/javascript">
		var BASEURL			= '<?php echo BASEURL; ?>';
		var CURRENT_THEME 	= '<?php echo $_SESSION['theme']; ?>';
		
		if( !strlen( CURRENT_THEME ) ) {
			CURRENT_THEME = '<?php echo DEFAULT_JQUERY_UI_THEME; ?>';			
		}

		if( DEBUG ) {
			console.log( 'Selected jQuery UI Theme:  ' + CURRENT_THEME );			
		}				
			
		$(document).ready(function() {			
	    	$('#switcher').themeswitcher( { 
		    	loadTheme: CURRENT_THEME, 
		    	cookieExpires: 3650, 
		    	cookiePath: '/',		   
		    	cookieName: 'theme', 
		    	cookieOnSet: function( cookieName, cookieValue ) {
		    		$.post( BASEURL + '/', { sessionUpdate: true, theme: cookieValue },
							function( data ) {
	
							}
					);					
			    },
		    	onSelect: function() {
		    		blockUIWithMessage( '', '', 1500 );		    			
	        	},
	        	onSelectComplete: function() {		     
	        		
	        	}
	    	});	    	
		});			
	</script>
    
    <?php if( @$clockedIn AND displayModal ) {
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
    } elseif( !@$clockedIn AND displayModal ) {
    ?>
	<script type="text/javascript">
	$(function() {
		$('#dialog-message').dialog({
		    width: 460,
			modal: true,
			buttons: {
				'Clock In': function() {
                    $.post('index.php', { clockIn: 'true' },
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

<?php if( displayModal ) { ?>
<?php if( @$clockedIn ) {
?>
<div id="dialog-message" title="Clocked in" style="display: none;">
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
            layout: 'Clocked in for:&nbsp;&nbsp;{hn} {hl} {mn} {ml} {sn} {sl}'
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
<?php } ?>

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
    		$('body').fadeIn();
    		$('#dialog-message').fadeIn();    		

    		<?php if( @$clockedIn ) { ?>
    		$( '#clockOutBtn' ).button( { icons: { primary: 'ui-icon-stop' } } );
    		$( '#clockOutBtn' ).click(function() {
    			blockUIWithMessage();        		
                $.post('index.php', { clockOutAll: 'true' },
                        function(data) {
                            if(data == 'OK') {
                                $('#list').trigger('reloadGrid');
                                window.location.reload();					            
                            }
				});
        	});
    		<?php } else { ?>
    		$( '#clockInBtn' ).button( { icons: { primary: 'ui-icon-play' } } );
    		$( '#clockInBtn' ).click(function() {
    			blockUIWithMessage();
                $.post('index.php', { clockIn: 'true' },
                        function(data) {
                            if(data == 'OK') {
                                $('#list').trigger('reloadGrid');
                                window.location.reload();					            
                            }
				});
        	});    		 
    		<?php } ?>   		 
		});
	</script>
	
<div id="logo">
	<a href="<?php echo BASEURL; ?>"><img src="img/logo.png" border="0"></a>
</div>	

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
	html { font-size: 70% }
	body { font-size: 100% }
</style>

<?php if( @$clockedIn ) {
?>
<!--  START:	logged in message -->
<div class="ui-widget">
	<div style="margin-top: 20px; padding: 10px;" class="ui-state-highlight ui-corner-all">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td class="ui-icon ui-icon-clock">				
				</td>
				
				<td style="padding-left: 5px;">
						You are currently clocked in.
    					<br /><br />
    					Clock in time:&nbsp;&nbsp;<?php echo date( 'l, F d, Y H:i:s', $data[0]['inTimestamp'] ) ?>				
				</td>			
			</tr>
						
			<tr>
				<td>
				</td>
				<td id="sinceCountdownWidget" style="white-space: nowrap; padding-left: 5px;">
				</td>
    			<script type="text/javascript">
        			$('#sinceCountdownWidget').countdown({
            			since: new Date('<?php echo date( 'F d, Y H:i:s', $data[0]['inTimestamp'] ) ?>'),
            			format: 'HMS',
            			layout: 'Clocked in for:&nbsp;&nbsp;{hn} {hl} {mn} {ml} {sn} {sl}'
        			});
    			</script>							
			</tr>
			
			<tr>
				<td>
				</td>
				<td style="padding-top: 5px;">
					<button id="clockOutBtn">Clock Out</button>
				</td>							
			</tr>
		</table>		 				
	</div>
</div>
<!--  END:	logged in message -->
<?php } else { ?>
<!--  START:	logged out message -->
<div class="ui-widget">
	<div style="margin-top: 20px; padding: 10px;" class="ui-state-highlight ui-corner-all">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td class="ui-icon ui-icon-clock">				
				</td>
				
				<td style="padding-left: 5px;">
					You are not currently clocked in.				
				</td>			
			</tr>
									
			<tr>
				<td>
				</td>
				<td style="padding-top: 5px;">
					<button id="clockInBtn">Clock In</button>
				</td>							
			</tr>
		</table>		 				
	</div>
</div>
<!--  END:	logged out message -->
<?php } ?>

<!--  START:	blockUI on page load -->
<div style="display: none;" class="blockUI"></div>
<div style="z-index: 1000; position: fixed;" class="blockUI blockOverlay ui-widget-overlay"></div>
<div style="z-index: 1011; position: fixed; width: 30%; top: 40%; left: 35%;" class="blockUI blockMsg blockPage ui-dialog ui-widget ui-corner-all ui-widget-content ui-draggable">
	<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">Loading</div>
	<div class="ui-widget-content ui-dialog-content">
		<p>Loading, please wait...</p>
	</div>
</div>
<!--  END:		blockUI on page load -->

<div id="switcher" style="margin-top: 10px; margin-right: 10px; position: fixed; right: 0;"></div>

</body>
</html>