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
						href:'<?=base_url()?>cashprocessing/show_form?index='+index+'&id='+<?=$id?>,
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
	</script>
	
	<article class="container_12">
	
	<section class="grid_12">
			<div class="block-border"><form class="block-content form" id="complex_form" method="post" action="#">
				<h1>Data Input Run Sheet</h1>
				
				<div class="block-controls">
					
					<ul class="controls-tabs js-tabs">
						<li class="current"><a href="#tab-dirs" title="Data Input Run Sheet"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Bar-Chart.png" width="24" height="24"></a></li>
						<li><a href="#tab-drs" title="Data Run Sheet"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Comment.png" width="24" height="24"></a></li>
					</ul>
					
				</div>
				<div class="columns">
					<div class="col200pxL-left">
						
						<h2>Run Sheet Preparation</h2>
						
						<ul class="side-tabs js-tabs same-height">
							<li><a href="#tab-dirs" title="Data Input Run Sheet">Data Input Run Sheet</a></li>
							<li><a href="#tab-drs" title="Data Run Sheet">Data Run Sheet</a></li>
						</ul>
						
				<div class="block-border grid_10">
					<div class="block-content no-title dark-bg"><p align="center"><b>Calendar</b></p>
					<div class="mini-calendar">
						<div class="calendar-controls">
							<a href="javascript:void(0)" class="calendar-prev" title="Previous month"><img src="<?=base_url()?>constellation/assets/images/cal-arrow-left.png" width="16" height="16"></a>
							<a href="javascript:void(0)" class="calendar-next" title="Next month"><img src="<?=base_url()?>constellation/assets/images/cal-arrow-right.png" width="16" height="16"></a>
							June 2019
						</div>
						
						<table cellspacing="0">
							<thead>
								<tr>
									<th scope="col" class="week-end">S</th>
									<th scope="col">M</th>
									<th scope="col">T</th>
									<th scope="col">W</th>
									<th scope="col">T</th>
									<th scope="col">F</th>
									<th scope="col" class="week-end">S</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="week-end other-month">28</td>
									<td class="other-month">29</td>
									<td class="other-month">30</td>
									<td class="other-month">31</td>
									<td><a href="javascript:void(0)">1</a></td>
									<td><a href="javascript:void(0)">2</a></td>
									<td class="week-end"><a href="javascript:void(0)">3</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">4</a></td>
									<td><a href="javascript:void(0)">5</a></td>
									<td><a href="javascript:void(0)">6</a></td>
									<td><a href="javascript:void(0)">7</a></td>
									<td><a href="javascript:void(0)">8</a></td>
									<td class="today"><a href="javascript:void(0)">9</a></td>
									<td class="week-end"><a href="javascript:void(0)">10</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">11</a></td>
									<td><a href="javascript:void(0)">12</a></td>
									<td><a href="javascript:void(0)">13</a></td>
									<td><a href="javascript:void(0)">14</a></td>
									<td><a href="javascript:void(0)">15</a></td>
									<td><a href="javascript:void(0)">16</a></td>
									<td class="week-end"><a href="javascript:void(0)">17</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">18</a></td>
									<td><a href="javascript:void(0)">19</a></td>
									<td><a href="javascript:void(0)">20</a></td>
									<td><a href="javascript:void(0)">21</a></td>
									<td><a href="javascript:void(0)">22</a></td>
									<td><a href="javascript:void(0)">23</a></td>
									<td class="week-end"><a href="javascript:void(0)">24</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">25</a></td>
									<td class="unavailable">26</td>
									<td class="unavailable">27</td>
									<td class="unavailable">28</td>
									<td><a href="javascript:void(0)">29</a></td>
									<td><a href="javascript:void(0)">30</a></td>
									<td class="week-end other-month">1</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					</div>
					</div>
						

			
			<section class="grid_12">
			
			
			</section>
					</div>
					<div class="col200pxL-right">
						
						<div id="tab-dirs" class="tabs-content" style="height:530px">
							
							<ul class="tabs js-tabs same-height">
								<li class="current"><a class="tab-hio" href="#tab-hio" title="Input Operasional">Input Operasional</a></li>
								<li><a href="#tab-hsc" class="tab-hsc" title="Security Control">Security Control</a></li>
								<li><a href="#tab-hcp" class="tab-hcp" title="Cash Processing">Cash Processing</a></li>
								<li><a href="#tab-hil" class="tab-hil" title="Input Logistic">Input Logistic</a></li>
								<li><a href="#tab-hrirs" title="Input Logistic">Review Input Run Sheet</a></li>
							</ul>
							
							<div class="tabs-content">
								
								<div id="tab-hio" style="height: 490px; display: block;">
														
								</div>
								<div id="tab-hsc">
									
								</div>
								<div id="tab-hcp">
									<table id="dg" class="easyui-datagrid" title="Cash Transit <?=$branch?>" style="width:100%;height:550px"
										data-options="rownumbers:true,singleSelect:true,url:'<?=base_url()?>cashprocessing/get_data/<?=$id?>',method:'get'" toolbar="#toolbar" pagination="true">
										<thead>
											<tr>
												<th data-options="field:'run_number',width:130">Run Sheet Area</th>
												<th data-options="field:'petty_cash',width:350">Petty Cash</th>
											</tr>
										</thead>
									</table>
									
									<div id="toolbar">
										<a href="#&tab-hcp" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">New</a>
										<a href="#&tab-hcp" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">Destroy</a>
										<a href="<?=base_url()?>cashprocessing#&tab-hcp" class="easyui-linkbutton" iconCls="icon-back" plain="true">Back</a>
									</div>
								</div>
								<div id="tab-hil">
									
								</div>
								<div id="tab-hrirs">
								
								</div>
							</div>
						</div>
						
						<div id="tab-drs" class="tabs-content" style="height:560px">
							
						</div>
							
					</div>
					
				<!-- THIS PLACE FOR MINI CALENDAR-->
				
				
				
				</div>
				
			</form></div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection