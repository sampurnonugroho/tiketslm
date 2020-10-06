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
						href:'<?=base_url()?>cashprocessing/show_form?index='+index+'&state='+row.state+'&row='+JSON.stringify(row)+'&id='+row.id+'&id_ct='+<?=$id?>,
						onLoad:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
							$('#dg').datagrid('selectRow',index);
							$('#dg').datagrid('getRowDetail',index).find('form').form('load',row);
						}
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});

			$('#dg2').datagrid({
				view: detailview,
				detailFormatter:function(index,row){
					return '<div class="ddv"></div>';
				}, 
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					
					console.log(row);
					ddv.panel({
						border:false,
						cache:true,
						// href:'show_form.php?index='+index,
						href:'<?=base_url()?>cashprocessing/show_form?index='+index+'&state='+row.state+'&row='+JSON.stringify(row)+'&id='+row.id+'&id_ct='+<?=$id?>,
						onLoad:function(){
							$('#dg2').datagrid('fixDetailRowHeight',index);
							$('#dg2').datagrid('selectRow',index);
							$('#dg2').datagrid('getRowDetail',index).find('form').form('load',row);
						}
					});
					$('#dg2').datagrid('fixDetailRowHeight',index);
				}
			});
		});

		function saveItemCit(index) {
			var row = $('#dg2').datagrid('getRows')[index];

			var url = row.isNewRecord ? '<?=base_url()?>cashprocessing_cit/save_data' : '<?=base_url()?>cashprocessing_cit/update_data?id='+row.id;
			console.log(url);
			$('#dg2').datagrid('getRowDetail',index).find('form').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					console.log(data);
					data = eval('('+data+')');
					data.isNewRecord = false;
					$('#dg2').datagrid('collapseRow',index);
					$('#dg2').datagrid('updateRow',{
						index: index,
						row: data
					});
				}, error: function(e) {
					console.log(e);
				}
			});
		}

		function cancelItemCit(index) {
			var row = $('#dg2').datagrid('getRows')[index];
			if (row.isNewRecord){
				$('#dg2').datagrid('deleteRow',index);
			} else {
				$('#dg2').datagrid('collapseRow',index);
			}
		}

		function saveItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			// console.log(row);
			var url = row.isNewRecord ? '<?=base_url()?>cashprocessing/save_data' : '<?=base_url()?>cashprocessing/update_data?id='+row.id;
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
				}, error: function(e) {
					console.log(e);
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
						$.post('<?=base_url()?>cashprocessing/delete_data',{id:row.id},function(){
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
			if(num==undefined || num==0) {
				return '<span style="font-size: 10px">N/A</span>';
			} else {
				return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");																	
			}
		}
	
		$(function(){
			$('#tt').tabs({
				border:false,
				onSelect:function(title){
					// alert(title+' is selected');
				}
			});
		});
	</script>
	
	<div id="tt" class="easyui-tabs" style="width:100%+1px;height:vh;">
		<div title="Processing Replenish" style="padding:20px;display:none;">
			<div class="block-border">
				<form class="block-content form" id="complex_form" method="post" action="#">
					<h1>Data Input Runsheet Cashreplenish</h1>
					<br>
					<table id="dg" class="easyui-datagrid" title="Cash Transit <?=$branch?>" style="width:100%;height:550px"
						data-options="
							rownumbers:true,
							singleSelect:true,
							url:'<?=base_url()?>cashprocessing/get_data/<?=$id?>',
							method:'get',
							rowStyler: function(index,row){
								console.table(row);
								if (row.cart_1_seal!=null){
									return 'background-color:#e6efff;color:black;font-weight:normal;';
								} else {
									return 'background-color:#ffe48d;color:black;font-weight:normal;';
								}
							}" toolbar="#toolbar" pagination="true">
						<thead>
							<tr>
								<th data-options="field:'wsid',width:90">ID</th>
								<!--<th data-options="field:'branch',width:100">BRANCH</th>-->
								<th data-options="field:'runsheet',width:90">GA</th>
								<th data-options="field:'bank',width:180">BANK</th>
								<th data-options="field:'act',width:90">ACT</th>
								<!--<th data-options="field:'brand',width:140">BRAND</th>-->
								<!--<th data-options="field:'model',width:140">MODEL</th>-->
								<th data-options="field:'detail_denom',width:160,align:'left'">DENOM</th>
								<th data-options="field:'ctr',width:100,align:'center',formatter:formatdecimal">CART</th>
								<th data-options="field:'total',width:200,align:'center',formatter:formatdecimal">TOTAL</th>
								<th data-options="field:'pcs_100000',width:120,align:'center',formatter:formatdecimal">100,000</th>
								<th data-options="field:'pcs_50000',width:120,align:'center',formatter:formatdecimal">50,000</th>
								<th data-options="field:'pcs_20000',width:120,align:'center',formatter:formatdecimal">20,000</th>
								<th data-options="field:'pcs_10000',width:120,align:'center',formatter:formatdecimal">10,000</th>
								<th data-options="field:'pcs_5000',width:120,align:'center',formatter:formatdecimal">5,000</th>
								<th data-options="field:'pcs_2000',width:120,align:'center',formatter:formatdecimal">2,000</th>
								<th data-options="field:'pcs_1000',width:120,align:'center',formatter:formatdecimal">1,000</th>
								<!--<th data-options="field:'pcs_coin',width:120,align:'center',formatter:formatdecimal">COIN</th>-->
							</tr>
						</thead>
					</table>
					
					<div id="toolbar">
						<a href="<?=base_url()?>cashprocessing#&tab-hcp" class="easyui-linkbutton" iconCls="icon-back" plain="true">Back</a>
					</div>
				</form>
			</div>
		</div>
		<div title="Processing Cashtransit" data-options="closable:false" style="overflow:auto;padding:20px;display:none;">
			<div class="block-border">
				<form class="block-content form" id="complex_form" method="post" action="#">
					<h1>Data Input Runsheet Cashtransit</h1>
					<br>
					<table id="dg2" class="easyui-datagrid" title="Cash Transit <?=$branch?>" style="width:100%;height:550px"
						data-options="rownumbers:true,singleSelect:true,url:'<?=base_url()?>cashprocessing_cit/get_data/<?=$id?>',method:'get'" toolbar="#toolbar2" pagination="true">
						<thead>
							<tr>
								<th data-options="field:'id',width:50" rowspan="2">ID</th>
								<th data-options="field:'metode',width:130" rowspan="2">METODE</th>
								<th data-options="field:'jenis',width:60" rowspan="2">JENIS</th>
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
					
					<div id="toolbar2">
						<a href="<?=base_url()?>cashprocessing#&tab-hcp" class="easyui-linkbutton" iconCls="icon-back" plain="true">Back</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
        function cellStyler(value,row,index){
            if (value < 30){
                return 'background-color:#ffee00;color:red;';
            }
        }
    </script>
@endsection