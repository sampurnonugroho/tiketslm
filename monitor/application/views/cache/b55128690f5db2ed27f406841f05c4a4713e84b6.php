<?php $__env->startSection('content'); ?>
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
	<script src="<?=base_url()?>assets/notify.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		jq341 = jQuery.noConflict();
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "1nd loaded jQuery version ($): " + $.fn.jquery + "<br>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
		// $ = jq341;
		
		
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
						href:'<?=base_url()?>logistic/show_form?index='+index+'&date=<?=$date?>',
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
			var url = row.isNewRecord ? '<?=base_url()?>logistic/save_data' : '<?=base_url()?>logistic/update_data?id='+row.id;
			console.log(url);
			$('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					console.log(data);
					jq341.notify("Data Logistic, Berhasil Disimpan", "success");
					data = eval('('+data+')');
					data.isNewRecord = false;
					$('#dg').datagrid('collapseRow',index);
					$('#dg').datagrid('updateRow',{
						index: index,
						row: data
					});
					
					// window.location.reload();
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
			var index = $('#dg').datagrid('getRowIndex',row);
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove this user?',function(r){
					if (r){
						console.log(row);
						$.post('<?=base_url()?>logistic/delete_data',{id:row.id},function(){
							jq341.notify("Data Logistic, Berhasil Dihapus", "warn");
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
	<?php 
		// echo "<pre>";
		// print_r($inventory);
		// echo "</pre>";
		// foreach($inventory as $id => $r) {
			// echo $id;
		// }
	?>
	<article class="container_12">
	<section class="grid_12">
			<div class="block-border"><form class="block-content form" id="complex_form" method="post" action="#">
				<h1>Data Input Run Sheet</h1>
				
				<table id="dg" class="easyui-datagrid" title="Cash Transit <?=$branch?>" style="width:100%;height:550px"
					data-options="rownumbers:true,singleSelect:true,url:'<?=base_url()?>logistic/get_data/<?=$date?>',method:'get'" toolbar="#toolbar" pagination="true">
					<thead>
						<tr>
							<th data-options="field:'run_number',width:230">Run Sheet Area</th>
							<!--<th data-options="field:'id_inv',width:250" hidden="true">id inv</th>-->
							<th data-options="field:'datas',width:450" hidden="true">Item Name</th>
							<?php
								foreach($inventory as $id => $r) {
							?>
									<th data-options="field:'<?=$r->id?>',width:150"><?=$r->name?></th>
							<?php
								}
							?>
						</tr>
					</thead>
				</table>
				
				<div id="toolbar">
					<?php if($session->userdata['level']=="LEVEL1") { ?>
						<a href="#&tab-hil" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">New</a>
						<a href="#&tab-hil" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">Destroy</a>
					<?php } ?>
					<a href="<?=base_url()?>logistic#&tab-hil" class="easyui-linkbutton" iconCls="icon-back" plain="true">Back</a>
				</div>
				
			</form></div>
		</section>
		
		<div class="clear"></div>
		
	</article>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>