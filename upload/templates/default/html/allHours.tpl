<script type="text/javascript">
jQuery(document).ready(function() {literal}{{/literal}
  jQuery("#list").jqGrid({literal}{{/literal}
    // http://www.trirand.com/jqgridwiki/doku.php?id=wiki:options
    altRows: true,
    url:'{$smarty.const.BASEURL}/index.php',
    altRows: true,
    autowidth: true,
    height: '100%',
    datatype: 'json',
    mtype: 'POST',
    loadtext: 'Loading&nbsp;&nbsp;' + '<img style="vertical-align: middle;" src="{$smarty.const.BASEURL}/img/ajax-loader.gif" border="0">',
    colNames:['Month', 'Day', 'Year', 'Clock In Time', 'Clock Out Time', 'Hours Worked this Day', 'Daily Balance', 'Hours Worked this Week', 'Weekly Balance'],
    colModel :[
      {literal}{{/literal}name:'month', index:'month', editrules:{literal}{{/literal} required: true {literal}}{/literal},editable:true,editoptions:{literal}{{/literal}style:"width:377px"{literal}}{/literal}{literal}}{/literal},
      {literal}{name:'day', index:'day', editrules:{ required: true },editable:true,editoptions:{style:"width:377px"}},
      {name:'year', index:'year', align:'center', editrules:{ required: true },editable:true,editoptions:{style:"width:377px"}},
      {name:'clock_in', index:'inTime', align:'center', editrules:{ required: true },editable:true,editoptions:{style:"width:377px"}},
      {name:'clock_out', index:'outTime', align:'center', editrules:{ required: true },editable:true,editoptions:{style:"width:377px"}},
      {name:'total_hours', index:'totalHours', sortable:false, align:'center', editrules:{ required: true },editable:true,edittype:"select"},
      {name:'hours_over', index:'hoursOver', sortable:false, align:'center', editable:true, edittype:"checkbox"},
      {name:'weekHours', index:'weekHours', sortable:false, align:'center', editable:true, edittype:"checkbox"},
      {name:'weekBalance', index:'weekBalance', sortable:false, align:'center', editable:true, edittype:"checkbox"}{/literal},
    ],
    pager: '#pager',
    rowNum:30,
    rowList:[1,2,5,10,15,20,25,30,60,90,120,180,365],
    sortname: 'month',
    sortorder: 'desc',
    viewrecords: true,
    editurl: '{$smarty.const.BASEURL}/index.php',
    subGrid: true,
    subGridUrl: '{$smarty.const.BASEURL}/subgrid.php',
    subGridModel: [{literal}{{/literal}
        name:  ['Hours worked this week',
                'Days worked this week',
                'Hours worked this month',
                'Days worked this month',
                'Hours worked this year',
                'Days worked this year'
                ],
        width: [200, 200, 200, 200, 200, 200],
        align: ['center', 'center', 'center', 'center', 'center', 'center'],
        params: ['month', 'day', 'year', 'clock_in']
    }]
    // http://www.trirand.com/jqgridwiki/doku.php?id=wiki:navigator
  {literal}}{/literal}).navGrid('#pager',{literal}{{/literal}add: false, del: false, edit: false, view: true, search: true{literal}}{/literal},
  {literal}{{/literal}left: 300, top: 10, width: 500, closeAfterEdit: true{literal}}{/literal}, {literal}{{/literal}left: 300, top: 10, width: 500, closeAfterAdd: true{literal}}{/literal},
  {literal}{}{/literal},{literal}{{/literal}width: 500{literal}}{/literal}
);  
{literal}}{/literal});
</script>

<table id="list"></table>
<div id="pager"></div>