<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Time Clock</title>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/base/jquery-ui.css">
	
	{switch strtolower( $smarty.session.theme ) }
		{case "absolution" break}
			<link rel="stylesheet" href="../../css/jquery-ui/{$smarty.session.themeString}/absolution.css">
			
		{case "aristo" break}
			<link rel="stylesheet" href="../../css/jquery-ui/{$smarty.session.themeString}/aristo.css">

		{case "delta" break}
		{case "facebook" break}
		{case "selene" break}
			<link rel="stylesheet" href="../../css/jquery-ui/{$smarty.session.themeString}/jquery-ui.css">						
			
		{default}
			<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/{$smarty.session.themeString}/jquery-ui.css">
	{/switch}
	
	<script type="text/javascript">
		var DEBUG = true;
	</script>
	
	<script src="../js/jquery-1.7.2.min.js"></script>
    <script src="../js/jquery-ui-1.8.19.custom.min.js"></script>        
    <script src="../js/consolelog.min.js"></script>
    
    <!--  blockUI -->
    <script src="../js/jquery.blockUI.js" type="text/javascript"></script>
    
    <!--  custom functions -->
    <script src="../js/functions.js?{math equation='rand()'}"></script>
    
    <!-- php.js -->
	<script src="../js/phpjs.js?{math equation='rand()'}" type="text/javascript"></script>
    
    <!-- jQuery UI Themeswitcher -->        
	<script src="../js/themeswitcher.js?{math equation='rand()'}" type="text/javascript"></script>
    
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
		    		// reloadPage();		    			
	        	{literal}}{/literal},
	        	onSelectComplete: function() {literal}{{/literal}		     
	        		
	        	{literal}}{/literal},
	        	appendThemes: function(switcherpane) {literal}{{/literal}
	        		//$('#themeGalleryList').append('<li style="width: 120px; padding: 2px; margin: 1px; border: 1px solid rgb(17, 17, 17); clear: left; float: left;"><a href="' + BASEURL + '/css/jquery-ui/aristo/aristo.css" style="color: rgb(170, 170, 170); text-decoration: none; float: left; width: 100%; outline: 0pt none;">			<img title="Test" alt="Test" src="http://jqueryui.com/themeroller/images/themeGallery/theme_90_swanky_purse.png" style="float: left; border: 1px solid rgb(51, 51, 51); margin: 0pt 2px;">			<span class="themeName" style="float: left; margin: 3px 0pt;">Test</span>			</a></li>');
			    {literal}}{/literal}
	    	{literal}}{/literal});	
	    	
			$.unblockUI();
		{literal}}{/literal});			
	</script>
	
<div id="logo">
	<a href="{$smarty.const.BASEURL}"><img src="../img/logo.png" border="0"></a>
</div>	

<div id="mainBody" class="ui-widget-content">		
	DEFAULT_JQUERY_UI_THEME
</div>

<style type="text/css">
	html {literal}{{/literal} font-size: 70% {literal}}{/literal}
	body {literal}{{/literal} font-size: 100% {literal}}{/literal}
</style>

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