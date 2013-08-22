<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Time Clock</title>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
</head>
<body>
	<div style="height: 85%; width: 100%; margin-left: auto; margin-right: auto;">	
		<div id="dialog-modal" title="DB Error" style="display: none;">
			<p>__ERROR_TEXT__</p>
		</div>	
	</div>
	
	<script type="text/javascript">
		$(function() {
			$( "#dialog-modal" ).dialog({
				dialogClass: "no-close",
				autoOpen: true,
				position: { 
							my: "center", 
							at: "center" 
				},				
				closeOnEscape: false,
				height: 140,
				modal: true
			});
		});
	</script>
	
	<style type="text/css">
		.no-close .ui-dialog-titlebar-close {
			display: none;
		}	
	</style>
		
</body>
</html>