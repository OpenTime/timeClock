<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Time Clock</title>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/base/jquery-ui.css">
	
	{switch $smarty.session.theme}
		{case "aristo" break}
			<link rel="stylesheet" href="{$smarty.const.BASEURL}/css/jquery-ui/{$smarty.session.themeString}/aristo.css">

		{default}
			<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/{$smarty.session.themeString}/jquery-ui.css">
	{/switch}
	
	<script type="text/javascript">
		var DEBUG = true;
	</script>
	
	<script src="{$smarty.const.BASEURL}/js/jquery-1.7.2.min.js"></script>
    <script src="{$smarty.const.BASEURL}/js/jquery-ui-1.8.19.custom.min.js"></script>        
    <script src="{$smarty.const.BASEURL}/js/consolelog.min.js"></script>
    <!--link rel="stylesheet" type="text/css" href="{$smarty.const.BASEURL}/css/jquery.countdown.css"-->
    <script type="text/javascript" src="{$smarty.const.BASEURL}/js/jquery.countdown.pack.js"></script>

    <link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.BASEURL}/css/jqGrid/ui.jqgrid.css" />
    <script src="{$smarty.const.BASEURL}/js/jqGrid/grid.locale-en.js" type="text/javascript"></script>
    <script src="{$smarty.const.BASEURL}/js/jqGrid/jquery.jqGrid.min.js" type="text/javascript"></script>
    
    <!--  blockUI -->
    <script src="{$smarty.const.BASEURL}/js/jquery.blockUI.js" type="text/javascript"></script>
    
    <!--  custom functions -->
    <script src="{$smarty.const.BASEURL}/js/functions.js?{math equation='rand()'}"></script>
    
    <!-- php.js -->
	<script src="{$smarty.const.BASEURL}/js/phpjs.js?{math equation='rand()'}" type="text/javascript"></script>
    
    <!-- jQuery UI Themeswitcher -->
	<!-- script src="http://jqueryui.com/themeroller/themeswitchertool/" type="text/javascript"></script-->	        
	<script src="{$smarty.const.BASEURL}/js/themeswitcher.js?{math equation='rand()'}" type="text/javascript"></script>
    
	<script type="text/javascript">
		var BASEURL			= '{$smarty.const.BASEURL}';
		var CURRENT_THEME 	= '{$smarty.session.theme}';
		
		{if !strlen( $smarty.const.CURRENT_THEME )}
			CURRENT_THEME = '{$smarty.const.DEFAULT_JQUERY_UI_THEME}';
		{/if}

		if( DEBUG ) {literal}{{/literal}
			console.log( 'Selected jQuery UI Theme:  ' + {$smarty.const.CURRENT_THEME} );			
		{literal}}{/literal}				
			
		$(document).ready(function() {literal}{{/literal}			
	    	$('#switcher').themeswitcher( {literal}{{/literal} 
		    	loadTheme: {$smarty.const.CURRENT_THEME}, 
		    	cookieExpires: 3650, 
		    	cookiePath: '/',		   
		    	cookieName: 'theme', 
		    	cookieOnSet: function( cookieName, cookieValue ) {literal}{{/literal}
		    		$.post( '{$smarty.const.BASEURL}', {literal}{{/literal} sessionUpdate: true, theme: cookieValue {literal}}{/literal},
							function( data ) {literal}{{/literal}
								
							{literal}}{/literal}
					);					
			    {literal}}{/literal},
		    	onSelect: function() {literal}{{/literal}
		    		blockUIWithMessage( '', '', 1500 );
		    		reloadPage();		    			
	        	{literal}}{/literal},
	        	onSelectComplete: function() {literal}{{/literal}		     
	        		
	        	{literal}}{/literal},
	        	appendThemes: function(switcherpane) {literal}{{/literal}
	        		//$('#themeGalleryList').append('<li style="width: 120px; padding: 2px; margin: 1px; border: 1px solid rgb(17, 17, 17); clear: left; float: left;"><a href="' + BASEURL + '/css/jquery-ui/aristo/aristo.css" style="color: rgb(170, 170, 170); text-decoration: none; float: left; width: 100%; outline: 0pt none;">			<img title="Test" alt="Test" src="http://jqueryui.com/themeroller/images/themeGallery/theme_90_swanky_purse.png" style="float: left; border: 1px solid rgb(51, 51, 51); margin: 0pt 2px;">			<span class="themeName" style="float: left; margin: 3px 0pt;">Test</span>			</a></li>');
			    {literal}}{/literal}
	    	{literal}}{/literal});	    	
		{literal}}{/literal});			
	</script>
    
    {if $clockedIn && $smarty.const.displayModal}

	<script type="text/javascript">
	$(function() {literal}{{/literal}
		$('#dialog-message').dialog({literal}{{/literal}
		    width: 460,
			modal: true,
			buttons: {literal}{{/literal}
				'Clock Out': function() {literal}{{/literal}
                    $.post('{$smarty.const.BASEURL}/index.php', {literal}{{/literal} clockOut: 'true', Id: {$data[0].Id}{literal}}{/literal},
                        function(data) {literal}{{/literal}
                            if(data == 'OK') {literal}{{/literal}
                                $('#list').trigger('reloadGrid');
					            $('#dialog-message').dialog('close');
                            {literal}}{/literal}
                    {literal}}{/literal});
				{literal}}{/literal}
			{literal}}{/literal}
		{literal}}{/literal});
	{literal}}{/literal});
	</script>
	
    {elseif !$clockedIn && $smarty.const.displayModal}
    
	<script type="text/javascript">
	$(function() {literal}{{/literal}
		$('#dialog-message').dialog({literal}{{/literal}
		    width: 460,
			modal: true,
			buttons: {literal}{{/literal}
				'Clock In': function() {literal}{{/literal}
                    $.post('{$smarty.const.BASEURL}/index.php', {literal}{{/literal} clockIn: 'true' {literal}}{/literal},
                        function(data) {literal}{{/literal}
                            if(data == 'OK') {literal}{{/literal}
                                reloadPage();
                                //$('#list').trigger('reloadGrid');
					            //$('#dialog-message').dialog('close');
                            {literal}}{/literal}
                    {literal}}{/literal});
				{literal}}{/literal}
			{literal}}{/literal}
		{literal}}{/literal});
	{literal}}{/literal});
	</script>

	{/if}
	
</head>
<body style="display: none;">

{if $smarty.const.displayModal}
{if $clockedIn}
<div id="dialog-message" title="Clocked in" style="display: none;">
	<p>
    You are currently clocked in.
    <br />
    Clock in time:&nbsp;&nbsp;{$data[0].inTimestamp|date_format:"l, F d, Y H:i:s"}
    </p>
    <div id="sinceCountdown" style="white-space: nowrap"></div>
    <script type="text/javascript">
        $('#sinceCountdown').countdown({literal}{{/literal}
            since: new Date( '{$data[0].inTimestamp|date_format:"F d, Y H:i:s"}' ),
            format: 'HMS',
            layout: 'Clocked in for:&nbsp;&nbsp;{literal}{hn} {hl} {mn} {ml} {sn} {sl}{/literal}'
        {literal}}{/literal});
    </script>
</div>

<div id="logout-message" title="Clocking out..." style="display: none;">
    <p>Clocking out. Please wait...</p>
</div>
{else}
<div id="dialog-message" title="Clocked in">
	<p>You are not currently clocked in.</p>
</div>
{/if}
{/if}

	<script type="text/javascript">
		$(function() {literal}{{/literal}          
			$( "#tabs" ).tabs({literal}{{/literal}
				ajaxOptions: {literal}{{/literal}
					error: function( xhr, status, index, anchor ) {literal}{{/literal}
						$( anchor.hash ).html(
							"Couldn't load this tab. We'll try to fix this as soon as possible. " +
							"If this wouldn't be a demo." );
					{literal}}{/literal}
				{literal}}{/literal}
			{literal}}{/literal});

    		$.unblockUI();
    		$('body').fadeIn();
    		$('#dialog-message').fadeIn();    		

    		{if $clockedIn}
    		$( '#clockOutBtn' ).button( {literal}{{/literal} icons: {literal}{{/literal} primary: 'ui-icon-stop' {literal}}{/literal} {literal}}{/literal} );
    		$( '#clockOutBtn' ).click(function() {literal}{{/literal}
    			blockUIWithMessage();        		
                $.post('{$smarty.const.BASEURL}/index.php', {literal}{{/literal} clockOutAll: 'true' {literal}}{/literal},
                        function(data) {literal}{{/literal}
                            if(data == 'OK') {literal}{{/literal}
                                $('#list').trigger('reloadGrid');
                                window.location.reload();					            
                            {literal}}{/literal}
				{literal}}{/literal});
        	});
    		{else}
    		$( '#clockInBtn' ).button( {literal}{{/literal} icons: {literal}{{/literal} primary: 'ui-icon-play' {literal}}{/literal} {literal}}{/literal} );
    		$( '#clockInBtn' ).click(function() {literal}{{/literal}
    			blockUIWithMessage();
                $.post('{$smarty.const.BASEURL}/index.php', {literal}{{/literal} clockIn: 'true' {literal}}{/literal},
                        function(data) {literal}{{/literal}
                            if(data == 'OK') {literal}{{/literal}
                                $('#list').trigger('reloadGrid');
                                window.location.reload();					            
                            {literal}}{/literal}
				{literal}}{/literal});
        	{literal}}{/literal});    		 
    		{/if}   		 
		{literal}}{/literal});
	</script>
	
<div id="logo">
	<a href="{$smarty.const.BASEURL}"><img src="{$smarty.const.BASEURL}/img/logo.png" border="0"></a>
</div>	

<div id="mainBody" class="ui-widget-content">		
	<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
				<a href="{$smarty.const.BASEURL}/?get=allHours">All Hours</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="{$smarty.const.BASEURL}/?get=settings">Settings</a>
			</li>
		</ul>
	</div>
</div>

<style type="text/css">
	html {literal}{{/literal} font-size: 70% {literal}}{/literal}
	body {literal}{{/literal} font-size: 100% {literal}}{/literal}
</style>

{if $clockedIn}
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
    					Clock in time:&nbsp;&nbsp;{$data[0].inTimestamp|date_format:"l, F d, Y H:i:s"}				
				</td>			
			</tr>
						
			<tr>
				<td>
				</td>
				<td id="sinceCountdownWidget" style="white-space: nowrap; padding-left: 5px;"></td>
    			<script type="text/javascript">
        			$('#sinceCountdownWidget').countdown({literal}{{/literal}
            			since: new Date('{$data[0].inTimestamp|date_format:"F d, Y H:i:s"}'),
            			format: 'HMS',
            			layout: 'Clocked in for:&nbsp;&nbsp;{literal}{hn} {hl} {mn} {ml} {sn} {sl}{/literal}'
        			{literal}}{/literal});
    			</script>							
			</tr>
			
			<tr>
				<td>
				</td>
				<td id="sinceCountdownLunchBreakWidget" style="white-space: nowrap; padding-left: 5px;"></td>
    			<script type="text/javascript">
        			$('#sinceCountdownLunchBreakWidget').countdown({literal}{{/literal}
            			since: new Date('{($data[0].inTimestamp + $smarty.const.lunchBreakDuration)|date_format:"F d, Y H:i:s"}'),
            			format: 'HMS',
            			layout: 'Total Hours worked (minus lunch break):&nbsp;&nbsp;{literal}{hn} {hl} {mn} {ml} {sn} {sl}{/literal}'
        			{literal}}{/literal});
    			</script>							
			</tr>
						
			<tr>
				<td>
				</td>
				<td id="freeToGoCount" style="white-space: nowrap; padding-left: 5px;"></td>
				{if $overTime }
    			<script type="text/javascript">
        			$('#freeToGoCount').countdown({literal}{{/literal}
            			since: new Date('{($data[0].inTimestamp + ( $smarty.const.requiredHoursPerDay * 3600 ))|date_format:"F d, Y H:i:s"}'),            			
            			format: 'HMS',
            			layout: '<font color="green">You are free to go now</font> (daily requirement met @ {($data[0].inTimestamp + ( $smarty.const.requiredHoursPerDay * 3600 ))|date_format:"l, F d, Y H:i:s"}). Overtime:&nbsp;&nbsp;{literal}{hn} {hl} {mn} {ml} {sn} {sl}{/literal}'
        			{literal}}{/literal});
    			</script>				
				{else}
    			<script type="text/javascript">
        			$('#freeToGoCount').countdown({literal}{{/literal}
            			until: new Date('{($data[0].inTimestamp + ( $smarty.const.requiredHoursPerDay * 3600 ))|date_format:"F d, Y H:i:s"}'),            			
            			onExpiry: reloadPage,
            			format: 'HMS',
            			layout: 'You are free to go in:&nbsp;&nbsp;{literal}{hn} {hl} {mn} {ml} {sn} {sl}{/literal}' + ', which is:&nbsp;&nbsp;{($data[0].inTimestamp + ( $smarty.const.requiredHoursPerDay * 3600 ))|date_format:"l, F d, Y H:i:s"}'
        			{literal}}{/literal});
    			</script>				
				{/if}							
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
{else}
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
{/if}

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

<div id="switcher" style="margin-top: 10px; margin-left: 0px; position: fixed;"></div>


</body>
</html>