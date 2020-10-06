@extends('layouts.master2')

@section('content')
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>constellation/assets/equipment/icon.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/demo.css">
	<style type="text/css">
		form{
			margin:0;
			padding:0;
		}
		.dv-table td{
			border:0;
		}
		.dv-table input{
			border:1px solid #ccc;
		}
	</style>
	
	<script type="text/javascript" src="<?=base_url()?>constellation/assets/equipment/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>constellation/assets/equipment/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>constellation/assets/equipment/datagrid-detailview.js"></script>
	
	
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	<script type="text/javascript">
		jq341 = jQuery.noConflict();
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "1nd loaded jQuery version ($): " + $.fn.jquery + "<br>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
	
		$(function(){
			$('#dg').datagrid({
				view: detailview,
				detailFormatter:function(index,row){
					return '<div class="ddv"></div>';
				},
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					ddv.panel({
						border:false,
						cache:true,
						// href:'show_form.php?index='+index,
						href:'<?=base_url()?>zone/show_form?index='+index+'&id='+<?=$id?>,
						onLoad:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
							$('#dg').datagrid('selectRow',index);
							$('#dg').datagrid('getRowDetail',index).find('form').form('load',row);
						}
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});
		});
		function saveItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			console.log(row);
			var url = row.isNewRecord ? '<?=base_url()?>zone/save_data' : '<?=base_url()?>zone/update_data?id='+row.id;
			console.log(url);
			$('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					console.log(data);
					data = eval('('+data+')');
					data.isNewRecord = false;
					$('#dg').datagrid('collapseRow',index);
					$('#dg').datagrid('updateRow',{
						index: index,
						row: data
					});
				}
			});
		}
		function cancelItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			if (row.isNewRecord){
				$('#dg').datagrid('deleteRow',index);
			} else {
				$('#dg').datagrid('collapseRow',index);
			}
		}
		function destroyItem(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove this user?',function(r){
					if (r){
						var index = $('#dg').datagrid('getRowIndex',row);
						console.log(row);
						$.post('<?=base_url()?>zone/delete_data',{id:row.id},function(){
							$('#dg').datagrid('deleteRow',index);
						});
					}
				});
			}
		}
		function newItem(){
			$('#dg').datagrid('appendRow',{isNewRecord:true});
			var index = $('#dg').datagrid('getRows').length - 1;
			$('#dg').datagrid('expandRow', index);
			$('#dg').datagrid('selectRow', index);
		}
		
		function doSearch(){
			// $('#dg').datagrid('load',{
				// sektor: $('#sektor').val()
			// });
			
			$('#dg').datagrid('load', {
				sektor: $('#sektor').val()
			});
		}
	</script>
	<div class="float-left">
			<button type="button" onclick="window.location.href='<?=base_url()?>zone'"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
	<article class="container_12">
	<section class="grid_12">
	<table id="dg" class="easyui-datagrid" title="Group Area Cabang <?=$branch?>" style="width:100%;height:550px"
		data-options="rownumbers:true,singleSelect:true,url:'<?=base_url()?>zone/get_data/<?=$id?>',method:'get'" toolbar="#toolbar" pagination="true">
        <thead>
            <tr>
                <th data-options="field:'kode',width:250">Area Number</th>
                <th data-options="field:'name',width:250">Area Name</th>
            </tr>
        </thead>
    </table>
	
	<div id="toolbar">
		<?php if($flag=="add"){ ?>
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">New</a>
		<?php } ?>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">Destroy</a>
	</div>
	
	</section>
		
		<div class="clear"></div>
		
	</article>
@endsection