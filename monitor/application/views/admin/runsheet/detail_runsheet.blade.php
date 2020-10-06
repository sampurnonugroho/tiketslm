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
		
		#preview_cr {
			float: right; 
			height: 803px; 
			width: 100%; 
			border: 1px solid #666; 
			-moz-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			-webkit-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		}
		
		#preview_cit {
			float: right; 
			height: 803px; 
			width: 100%; 
			border: 1px solid #666; 
			-moz-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			-webkit-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		}
	</style>
	
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery.easyui.min.js"></script>
	
	
	<script src="<?=base_url()?>assets/select2/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>assets/select2/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>assets/select2/select2.min.js"></script>

	<script type="text/javascript">
		jq341 = jQuery.noConflict(true);
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
						href:'<?=base_url()?>cashprocessing/show_form?index='+index+'&id='+<?=$id_ct?>,
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
		
		var url = window.location.href.replace(/\/$/, '');
		lastSeg = url.substr(url.lastIndexOf('/') + 1);
		var win;
		
		jq341(document).on('click', '#detail_preview_cr', function(){ 
			if(lastSeg=="demo") {
				console.log(win);
				if(win==undefined) {
					win = window.open("<?=base_url()?>pdf_fix/run/<?=$id_ct?>/<?=$id_zona?>", "_blank");
					win.focus();
				} else {
					win.location.href = "<?=base_url()?>pdf_fix/run/<?=$id_ct?>/<?=$id_zona?>";
					win.focus();
				}
			} else {
				jq341(".preview_pdf_cr").show();
				jq341(".preview_table_cr").hide();
				document.getElementById("preview_cr").src = "";
				setTimeout(function() {
					var websel = "<?=base_url()?>pdf_fix/run/<?=$id_ct?>/<?=$id_zona?>";
					document.getElementById("preview_cr").src = websel;
				}, 100);
			}
		});
		
		jq341(document).on('click', '#close_preview_cr', function(){ 
			jq341(".preview_pdf_cr").hide();
			jq341(".preview_table_cr").show();
		});

		jq341(document).on('click', '#detail_preview_cit', function(){ 
			if(lastSeg=="demo") {
				if(win==undefined) {
					win = window.open("<?=base_url()?>pdf_fix/boc/<?=$id_ct?>/<?=$id_zona?>", "_blank");
					win.focus();
				} else {
					win.location.href = "<?=base_url()?>pdf_fix/boc/<?=$id_ct?>/<?=$id_zona?>";
					win.focus();
				}
			} else {			
				jq341(".preview_pdf_cit").show();
				jq341(".preview_table_cit").hide();
				document.getElementById("preview_cit").src = "";
				setTimeout(function() {
					var websel = "<?=base_url()?>pdf_fix/boc/<?=$id_ct?>/<?=$id_zona?>";
					document.getElementById("preview_cit").src = websel;
				}, 100);
			}
		});
		
		jq341(document).on('click', '#close_preview_cit', function(){ 
			jq341(".preview_pdf_cit").hide();
			jq341(".preview_table_cit").show();
		});

		$(function(){
			$('#tt').tabs({
				border:false,
				onSelect:function(title){
					// alert(title+' is selected');
				}
			});
		});

		function formatdecimal(num){
			if(num==undefined) {
				return 0;
			} else {
				return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");																	
			}
		}
	</script>

	<div id="tt" class="easyui-tabs" style="width:100%+1px;height:vh;">
		<div title="Runsheet Replenish" style="padding:20px;display:none;">
			<div class="preview_pdf_cr" hidden>
				<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview_cr' type="button">Close</button>
				<iframe id="preview_cr" name="preview_cr" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
			</div>

			<div class="block-border preview_table_cr">
				<form class="block-content form" id="complex_form" method="post" action="#">
					<button style="margin-top: -25px; float: right" class="btn btn-primary pull-right" id='detail_preview_cr' type="button">Print Runsheet Replenish</button>
					<br>
					<fieldset class="grey-bg required" style="font-size: 12px">
						<section class="grid_4">
							<div class="block-border ">
								<fieldset>
									<table class="table sortable1 no-margin" cellspacing="0" width="115%">
										<thead>
											<tr>
												<th colspan="2" style="text-align:center;"></th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<th colspan="1" width="55%">RUN NUMBER</th>
												<td colspan="1"><?=$run_number?></td>
											</tr>
											<tr>
												<th colspan="1" width="55%">BRANCH</th>
												<td colspan="1"><?=$branch?></td>
											</tr>
											<tr>
												<th colspan="1">VEHICLE NUMBER</th>
												<td colspan="1"><?=$police_number?></td>
											</tr>
											<tr>
												<th colspan="1">KM</th>
												<td colspan="1"><?=$km?></td>
											</tr>
										</tbody>
									</table>
								</fieldset>
							</div>
						</section>
						<section class="grid_4">
							<div class="block-border ">
								<fieldset>
									<table class="table sortable1 no-margin" cellspacing="0" width="115%">
										<thead>
											<tr>
												<th colspan="2" style="text-align:center;"></th>
											</tr>
										</thead>
										<tbody>
											<?php 
												foreach($logistic as $r) {
											?>
											<tr>
												<th colspan="1" width="55%"><?=$r['name']?></th>
												<td colspan="1"><?=$r['qty']?></td>
											</tr>
											<?php 
												}
											?>
											<tr>
												<th colspan="1">PETTY CASH</th>
												<td colspan="1"><?=$petty_cash?></td>
											</tr>
										</tbody>
									</table>
								</fieldset>
							</div>
						</section>
						<section class="grid_4">
							<div class="block-border ">
								<fieldset>
									<table class="table sortable1 no-margin" cellspacing="0" width="115%">
										<thead>
											<tr>
												<th colspan="2" style="text-align:center;"></th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<th colspan="1" width="55%">CUSTODIAN 1</th>
												<td colspan="1"><?=$custodian_1?></td>
											</tr>
											<tr>
												<th colspan="1">CUSTODIAN 2</th>
												<td colspan="1"><?=($custodian_2=="" ? "-none-" : $custodian_2)?></td>
											</tr>
											<tr>
												<th colspan="1">GUARD 1</th>
												<td colspan="1"><?=$security_1?></td>
											</tr>
											<tr>
												<th colspan="1">GUARD 2</th>
												<td colspan="1"><?=($security_2=="" ? "-none-" : $security_2)?></td>
											</tr>
										</tbody>
									</table>
								</fieldset>
							</div>
						</section>
						<section class="grid_12 red">
							<div class="block-content">
								<table id="dg" class="easyui-datagrid" title="Cash Transit" style="width:100%;height:550px" data-options="rownumbers:true,singleSelect:true,url:'<?=base_url()?>runsheet/get_data_runsheet_atm/<?=$id_ct?>/<?=$id_zona?>',method:'get'" toolbar="#toolbar" pagination="true">
									<thead>
										<tr>
											<th data-options="field:'wsid',width:80">ID</th>
											<th data-options="field:'branch_name',width:150">BRANCH</th>
											<th data-options="field:'sektor',width:50">GA</th>
											<th data-options="field:'bank',width:100">BANK</th>
											<th data-options="field:'type',width:100">ACT</th>
											<th data-options="field:'vendor',width:100">BRAND</th>
											<th data-options="field:'type_mesin',width:100">MODEL</th>
											<th data-options="field:'lokasi',width:150">LOCATION</th>
											<th data-options="field:'pcs_100000',width:100">100K</th>
											<th data-options="field:'pcs_50000',width:100">50K</th>
											<th data-options="field:'pcs_20000',width:100">20K</th>
											<th data-options="field:'pcs_10000',width:100">10K</th>
											<th data-options="field:'pcs_5000',width:100">5K</th>
											<th data-options="field:'pcs_2000',width:100">2K</th>
											<th data-options="field:'pcs_1000',width:100">1K</th>
											<th data-options="field:'pcs_coin',width:100">COIN</th>
											<th data-options="field:'ctr2',width:100">TOTAL CART</th>
											<th data-options="field:'total',width:250">NOMINAL</th>
											<th data-options="field:'solve',width:250">STATUS</th>
											<!--<th data-options="field:'petty_cash',width:100">BAG SEAL</th>
													<th data-options="field:'petty_cash',width:100">BAG NO</th>
													<th data-options="field:'petty_cash',width:100">START AUTO SCAN</th>
													<th data-options="field:'petty_cash',width:100">END APPLICATION</th>-->
										</tr>
									</thead>
								</table>
							</div>
						</section>
					</fieldset>
				</form>
				<div id="toolbar">
					<a href="<?=base_url()?>runsheet" class="easyui-linkbutton" iconCls="icon-back" plain="true">Back</a>
				</div>
			</div>
		</div>
		<div title="Runsheet Cashtransit" data-options="closable:false" style="overflow:auto;padding:20px;display:none;">
		<div class="preview_pdf_cit" hidden>
				<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview_cit' type="button">Close</button>
				<iframe id="preview_cit" name="preview_cit" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
			</div>

			<div class="block-border preview_table_cit">
				<form class="block-content form" id="complex_form" method="post" action="#">
					<button style="margin-top: -25px; float: right" class="btn btn-primary pull-right" id='detail_preview_cit' type="button">Print Runsheet Cashtransit</button>
					<br>
					<fieldset class="grey-bg required" style="font-size: 12px">
						<section class="grid_4">
							<div class="block-border ">
								<fieldset>
									<table class="table sortable1 no-margin" cellspacing="0" width="115%">
										<thead>
											<tr>
												<th colspan="2" style="text-align:center;"></th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<th colspan="1" width="55%">RUN NUMBER</th>
												<td colspan="1"><?=$run_number?></td>
											</tr>
											<tr>
												<th colspan="1" width="55%">BRANCH</th>
												<td colspan="1"><?=$branch?></td>
											</tr>
											<tr>
												<th colspan="1">VEHICLE NUMBER</th>
												<td colspan="1"><?=$police_number?></td>
											</tr>
											<tr>
												<th colspan="1">KM</th>
												<td colspan="1"><?=$km?></td>
											</tr>
										</tbody>
									</table>
								</fieldset>
							</div>
						</section>
						<section class="grid_4">
							<div class="block-border ">
								<fieldset>
									<table class="table sortable1 no-margin" cellspacing="0" width="115%">
										<thead>
											<tr>
												<th colspan="2" style="text-align:center;"></th>
											</tr>
										</thead>
										<tbody>
											<?php 
												foreach($logistic as $r) {
											?>
											<tr>
												<th colspan="1" width="55%"><?=$r['name']?></th>
												<td colspan="1"><?=$r['qty']?></td>
											</tr>
											<?php 
												}
											?>
											<tr>
												<th colspan="1">PETTY CASH</th>
												<td colspan="1"><?=$petty_cash?></td>
											</tr>
										</tbody>
									</table>
								</fieldset>
							</div>
						</section>
						<section class="grid_4">
							<div class="block-border ">
								<fieldset>
									<table class="table sortable1 no-margin" cellspacing="0" width="115%">
										<thead>
											<tr>
												<th colspan="2" style="text-align:center;"></th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<th colspan="1" width="55%">CUSTODIAN 1</th>
												<td colspan="1"><?=$custodian_1?></td>
											</tr>
											<tr>
												<th colspan="1">CUSTODIAN 2</th>
												<td colspan="1"><?=($custodian_2=="" ? "-none-" : $custodian_2)?></td>
											</tr>
											<tr>
												<th colspan="1">GUARD 1</th>
												<td colspan="1"><?=$security_1?></td>
											</tr>
											<tr>
												<th colspan="1">GUARD 2</th>
												<td colspan="1"><?=($security_2=="" ? "-none-" : $security_2)?></td>
											</tr>
										</tbody>
									</table>
								</fieldset>
							</div>
						</section>
						<section class="grid_12 red">
							<div class="block-content">
								<table id="dg" class="easyui-datagrid" title="Cash Transit" style="width:100%;height:550px" data-options="rownumbers:true,singleSelect:true,url:'<?=base_url()?>runsheet/get_data_runsheet_cit/<?=$id_ct?>/<?=$id_zona?>',method:'get'" toolbar="#toolbar2" pagination="true">
									<thead>
										<tr>
											<th data-options="field:'id',width:50" rowspan="2">ID</th>
											<th data-options="field:'metode',width:130" rowspan="2">METODE</th>
											<th data-options="field:'jenis',width:60" rowspan="2">JENIS</th>
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
							</div>
						</section>
					</fieldset>
				</form>
				<div id="toolbar2">
					<a href="<?=base_url()?>runsheet" class="easyui-linkbutton" iconCls="icon-back" plain="true">Back</a>
				</div>
			</div>
		</div>
	</div>
@endsection