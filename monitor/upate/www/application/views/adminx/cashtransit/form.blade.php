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
		.focus {
			border-color:red;
		}
	</style>
	
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/datagrid-detailview.js"></script>
	
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

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
						href:'<?=base_url()?>cashtransit/show_form?index='+index+'&id='+<?=$id?>,
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
			var url = row.isNewRecord ? '<?=base_url()?>cashtransit/save_data' : '<?=base_url()?>cashtransit/update_data?id='+row.id;
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
				},
				error: function(e) {
					console.log(e)
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
						$.post('<?=base_url()?>cashtransit/delete_data',{id:row.id},function(){
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
		
		function formatdecimal(num){
			if(num==undefined) {
				return 0;
			} else {
				return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");																	
			}
		}
	</script>

	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.location.href='<?=base_url()?>cashtransit'"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
	</div></div>
	<!-- End control bar -->
	
	<article class="container_12">
		<section class="grid_12">
			<table id="dg" class="easyui-datagrid" title="Cash Transit" style="width:100%;height:550px"
				data-options="rownumbers:true,singleSelect:true,url:'<?=base_url()?>cashtransit/get_data/<?=$id?>',method:'get'" toolbar="#toolbar" pagination="true">
				<!--<thead frozen="false">
					<tr>
						<th data-options="field:'metode',width:130">METODE</th>
						<th data-options="field:'pengirim',width:110">PENGIRIM</th>
						<th data-options="field:'penerima',width:110">PENERIMA</th>
					</tr>
				</thead>-->
				<thead>
					<tr>
						<th data-options="field:'metode',width:130" rowspan="2">METODE</th>
						<th data-options="field:'pengirim',width:110" rowspan="2">PENGIRIM</th>
						<th data-options="field:'penerima',width:110" rowspan="2">PENERIMA</th>
						<th data-options="field:'id',width:50" rowspan="2">ID</th>
						<th data-options="field:'branch',width:100" rowspan="2">BRANCH</th>
						<th data-options="field:'runsheet',width:110" rowspan="2">GROUP AREA</th>
						<th colspan="7">UANG KERTAS</th>
						<th colspan="5">UANG LOGAM</th>
						<th data-options="field:'total',width:120,align:'center',formatter:formatdecimal" rowspan="2">TOTAL</th>
					</tr>
					<tr>
						<th data-options="field:'kertas_100k',width:120,align:'center',formatter:formatdecimal">100,000</th>
						<th data-options="field:'kertas_50k',width:120,align:'center',formatter:formatdecimal">50,000</th>
						<th data-options="field:'kertas_20k',width:120,align:'center',formatter:formatdecimal">20,000</th>
						<th data-options="field:'kertas_10k',width:120,align:'center',formatter:formatdecimal">10,000</th>
						<th data-options="field:'kertas_5k',width:120,align:'center',formatter:formatdecimal">5,000</th>
						<th data-options="field:'kertas_2k',width:120,align:'center',formatter:formatdecimal">2,000</th>
						<th data-options="field:'kertas_1k',width:120,align:'center',formatter:formatdecimal">1,000</th>
						<th data-options="field:'logam_1k',width:120,align:'center',formatter:formatdecimal">1,000</th>
						<th data-options="field:'logam_500',width:120,align:'center',formatter:formatdecimal">500</th>
						<th data-options="field:'logam_200',width:120,align:'center',formatter:formatdecimal">200</th>
						<th data-options="field:'logam_100',width:120,align:'center',formatter:formatdecimal">100</th>
						<th data-options="field:'logam_50',width:120,align:'center',formatter:formatdecimal">50</th>
					</tr>
				</thead>
			</table>

			<div id="toolbar">
				<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">New</a>
				<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">Destroy</a>
			</div>
		</section>
		<div class="clear"></div>
	</article>
@endsection