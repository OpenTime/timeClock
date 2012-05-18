<!-- 05:39 PM, March 08, 2011 -->
<script type="text/javascript">
jQuery(document).ready(function(){literal}{{/literal}
  jQuery("#settingsList").jqGrid({literal}{{/literal}
    // http://www.trirand.com/jqgridwiki/doku.php?id=wiki:options
    //altRows: true,
    url:'{$smarty.const.BASEURL}/index.php?settings=true',
    autowidth: true,
    height: '100%',
    datatype: 'json',
    mtype: 'POST',
    loadtext: 'Loading&nbsp;&nbsp;' + '<img style="vertical-align: middle;" src="{$smarty.const.BASEURL}/img/ajax-loader.gif" border="0">',
    colNames:['Key', 'Value', 'Comment'],
    colModel :[
      {literal}{name:'key', index:'key',editrules:{ required: true },editable:true,editoptions:{style:"width:377px"}},
      {name:'value', index:'value',editrules:{ required: true },editable:true,editoptions:{style:"width:377px"}},
      {name:'comment', index:'comment',editrules:{ required: true },editable:true,editoptions:{style:"width:377px"}}{/literal}
    ],
    pager: '#settingsPager',
    rowNum:30,
    rowList:[10,20,30,45,60,90],
    sortname: 'key',
    sortorder: 'asc',
    viewrecords: true,
    editurl: '{$smarty.const.BASEURL}/index.php?settings=true',
    // http://www.trirand.com/jqgridwiki/doku.php?id=wiki:navigator
  {literal}}{/literal}).navGrid('#settingsPager',{literal}{add: true, del: false, edit: true, view: true, search: true}{/literal},
  {literal}{left: 300, top: 10, width: 500, closeAfterEdit: true}, {left: 300, top: 10, width: 500, closeAfterAdd: true},
  {},{width: 500}{/literal}
);

{literal}}{/literal});
</script>

<table id="settingsList"></table>
<div id="settingsPager"></div>