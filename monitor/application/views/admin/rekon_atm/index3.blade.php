@extends('layouts.master')

@section('content')
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
		<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
		<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

		
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>depend/jquery-confirm/css/jquery-confirm.css"/>
		<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
		
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/datatables/datatables.min.css"/>
	 
		<script type="text/javascript" src="<?=base_url()?>/assets/datatables/datatables.min.js"></script>
		
		<script type="text/javascript" src="<?=base_url()?>assets/jquery.scannerdetection.js"></script>
		<script type="text/javascript" src="<?=base_url()?>assets/notify.min.js"></script>
		<script type="text/javascript" src="<?=base_url()?>assets/jquery.inputmask.js"></script>
		
		<style>
			.jconfirm .jconfirm-holder {
				max-height: 100%;
				padding: 54px 450px;
					padding-top: 54px;
					padding-bottom: 54px;
			}
			#preview {
				float: right; 
				height: 803px; 
				width: 100%; 
				border: 1px solid #666; 
				-moz-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
				-webkit-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
				box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			}
			.dataTables_filter {
				width: 40%;
				float: left !important;
				text-align: left !important;
			}
			.dataTables_length {
				float: right !important;
			}
			
			.select2-container {
				z-index: 99999999 !important;
			}
			
			.dataTables_paginate #datatable_previous {
				width: 60px !important;
			}
			
			.dataTables_paginate #datatable_next {
				width: 60px !important;
			}
			
			
			.view {
				margin: auto;
				width: 100%;
			}

			.wrapper {
				position: relative;
				overflow: auto;
				border: 1px solid black;
				white-space: nowrap;
			}

			.sticky-col {
				position: sticky;
				position: -webkit-sticky;
				background-color: white;
			}

			.first-col {
				width: 50px;
				min-width: 50px;
				max-width: 50px;
				left: 0px;
			}

			.second-col {
				width: 150px;
				min-width: 150px;
				max-width: 150px;
				left: 50px;
			}
			
			button.red, .red button, .big-button.red, .red .big-button {
				color: white;
				border-color: #bf3636 #5d0000 #0a0000;
				background: #790000 url(../images/old-browsers-bg/button-element-red-bg.png) repeat-x top;
				background: -moz-linear-gradient(top,white,#ca3535 4%,#790000);
				background: -webkit-gradient(linear,left top, left bottom,from(white),to(#790000),color-stop(0.03, #ca3535));
			}
			
			button.yellow, .yellow button, .big-button.yellow, .yellow .big-button {
				color: black;
				border-color: #ffcc00 #ffcc00 #ffcc00;
				background: #790000 url(../images/old-browsers-bg/button-element-yellow-bg.png) repeat-x top;
				background: -moz-linear-gradient(top,white,#ffff00 4%,#ffcc00);
				background: -webkit-gradient(linear,left top, left bottom,from(white),to(#ffcc00),color-stop(0.03, #ffff00));
			}
			
			button.green, .green button, .big-button.green, .green .big-button {
				color: black;
				border-color: #99ff33 #99ff33 #99ff33;
				background: #790000 url(../images/old-browsers-bg/button-element-green-bg.png) repeat-x top;
				background: -moz-linear-gradient(top,white,#99ff66 4%,#33cc33);
				background: -webkit-gradient(linear,left top, left bottom,from(white),to(#33cc33),color-stop(0.03, #99ff66));
			}
		</style>
		
		<style>
			.ui-datepicker {
				width: 17.8em !important;
			}
			#my_tables td {
				border: 0px solid white !important;
			}
		</style>
		
		
		<link rel="stylesheet" href="<?=base_url()?>depend/JQW/jqwidgets/styles/jqx.base.css" type="text/css" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1" />	
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/scripts/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxcore.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxdata.js"></script> 
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxbuttons.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxscrollbar.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxmenu.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.sort.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.filter.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.columnsresize.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.columnsreorder.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.pager.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.edit.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.selection.js"></script> 
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxpanel.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxcheckbox.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxlistbox.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxdropdownlist.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/scripts/demos.js"></script>
		
		<script type="text/javascript">
			var jq = jQuery.noConflict();
			 
			(function( $ ) {
				$(document).ready(function () {
					var theme = 'classic';
					
					var source = {
						datatype: "json",
						datafields: [
							{ name: 'no'},
							{ name: 'wsid'},
							{ name: 'lokasi'},
							{ name: 'type'},
							{ name: 'tanggal'},
							{ name: 'ctr'},
							{ name: 'D50'},
							{ name: 'D100'},
							{ name: 'T50'},
							{ name: 'T100'},
							{ name: 'CSST1_50'},
							{ name: 'CSST1_100'},
							{ name: 'CSST2_50'},
							{ name: 'CSST2_100'},
							{ name: 'CSST3_50'},
							{ name: 'CSST3_100'},
							{ name: 'CSST4_50'},
							{ name: 'CSST4_100'},
							{ name: 'RJT50'},
							{ name: 'RJT100'},
							{ name: 'TOTALA'},
							{ name: 'CSST1B50'},
							{ name: 'CSST1B100'},
							{ name: 'TOTALB'},
							{ name: 'JML_CSST02'},
							{ name: 'D50B'},
							{ name: 'T50B'},
							{ name: 'D100B'},
							{ name: 'T100B'},
						],
						url: '<?=base_url()?>rekon_atm/get_data3',
						root: 'Rows',
						cache: false
					};

					var dataAdapter = new $.jqx.dataAdapter(source);

					$("#jqxgrid").jqxGrid(
					{
						width: "100%",
						source: dataAdapter,
						theme: theme,
						pageable: true,
						autorowheight: true,
						altrows: true,
						columnsresize: true,
						columns: [
						  { text: 'NO', cellsalign: 'center', align: 'center', datafield: 'no', width: 50 },
						  { text: 'ATM ID', cellsalign: 'center', align: 'center', datafield: 'wsid', width: 80 },
						  { text: 'LOKASI', cellsalign: 'center', align: 'center', datafield: 'lokasi', width: 210 },
						  { text: 'ATM / CRM', cellsalign: 'center', align: 'center', datafield: 'type', width: 110 },
						  
						  { text: 'TANGGAL', columngroup: 'A2', datafield: 'tanggal', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 140 },
						  { text: 'JML CSST', columngroup: 'A3', datafield: 'ctr', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 80 },
						  { text: 'D 50', columngroup: 'A3', datafield: 'D50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: 'T 50', columngroup: 'A3', datafield: 'T50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: 'D 100', columngroup: 'A3', datafield: 'D100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: 'T 100', columngroup: 'A3', datafield: 'T100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  
						  { text: '50', columngroup: 'A33', datafield: 'CSST1_50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '100', columngroup: 'A33', datafield: 'CSST1_100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '50', columngroup: 'A44', datafield: 'CSST2_50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '100', columngroup: 'A44', datafield: 'CSST2_100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '50', columngroup: 'A55', datafield: 'CSST3_50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '100', columngroup: 'A55', datafield: 'CSST3_100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '50', columngroup: 'A66', datafield: 'CSST4_50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '100', columngroup: 'A66', datafield: 'CSST4_100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '50', columngroup: 'A77', datafield: 'RJT50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '100', columngroup: 'A77', datafield: 'RJT100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '50', columngroup: 'A88', datafield: 'RTC50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '100', columngroup: 'A88', datafield: 'RTC100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '(X1000)', columngroup: 'A99', datafield: 'TOTALA', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '50', columngroup: 'AA33', datafield: 'CSST1B50', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '100', columngroup: 'AA33', datafield: 'CSST1B100', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: '(X1000)', columngroup: 'AA44', datafield: 'TOTALB', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
						  { text: 'COUNTER<br>(KHUSUS CRM)<br>(X1000)', filterable: false, 
								cellsAlign: 'left', datafield: 'CCRM', width: '10%', cellsformat: 'D',
								renderer: function(row) {
									var res = '';
									if (row.indexOf('<br>') > -1) {
										res = '<div style="text-align: center; padding: 33px 3px 3px 3px;">'; 
									} else {            
										res = '<div style="text-align: center; padding: 4px 3px 3px 3px;">';
									}
									res += row +'</div>';
									
									return res;
								}
							},
						  { text: 'SELISIH<br>TOTAL RP.<br>(X1000)', filterable: false, 
								cellsAlign: 'left', datafield: 'SELTOTAL', width: '10%', cellsformat: 'D',
								renderer: function(row) {
									var res = '';
									if (row.indexOf('<br>') > -1) {
										res = '<div style="text-align: center; padding: 33px 3px 3px 3px;">'; 
									} else {            
										res = '<div style="text-align: center; padding: 4px 3px 3px 3px;">';
									}
									res += row +'</div>';
									
									return res;
								}
							},
							{ text: 'TIME<br>ALLOCATION', filterable: false, 
								cellsAlign: 'left', datafield: 'TIME', width: '10%', cellsformat: 'D',
								renderer: function(row) {
									var res = '';
									if (row.indexOf('<br>') > -1) {
										res = '<div style="text-align: center; padding: 33px 3px 3px 3px;">'; 
									} else {            
										res = '<div style="text-align: center; padding: 4px 3px 3px 3px;">';
									}
									res += row +'</div>';
									
									return res;
								}
							},
							{ text: 'JML CSST', columngroup: 'AB2', datafield: 'JML_CSST02', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 140 },
							{ text: 'D 50', columngroup: 'AB3', datafield: 'D50B', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
							{ text: 'T 50', columngroup: 'AB3', datafield: 'T50B', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
							{ text: 'D 100', columngroup: 'AB3', datafield: 'D100B', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
							{ text: 'T 100', columngroup: 'AB3', datafield: 'T100B', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 100 },
							{ text: 'KETERANGAN<br>NAIK/TURUN<br>LIMIT', filterable: false, 
								cellsAlign: 'left', datafield: 'KET_LIMIT', width: '12%', cellsformat: 'D',
								renderer: function(row) {
									var res = '';
									if (row.indexOf('<br>') > -1) {
										res = '<div style="text-align: center; padding: 33px 3px 3px 3px;">'; 
									} else {            
										res = '<div style="text-align: center; padding: 4px 3px 3px 3px;">';
									}
									res += row +'</div>';
									
									return res;
								}
							}
							
							
							
							
						  
						],
						columngroups: 
						[
						  { text: 'REKONSILIASI', align: 'center', name: 'A1' },
						  { text: 'PENGISIAN SEBELUMNYA', parentgroup: 'A1', align: 'center', name: 'A2' },
						  { text: 'TOTAL RP.', parentgroup: 'A2', align: 'left', name: 'A3' },
						  
						  { text: 'PERHITUNGAN', parentgroup: 'A1', align: 'center', name: 'A22' },
						  { text: 'CASSETE 01', parentgroup: 'A22', align: 'center', name: 'A33' },
						  { text: 'CASSETE 02', parentgroup: 'A22', align: 'center', name: 'A44' },
						  { text: 'CASSETE 03', parentgroup: 'A22', align: 'center', name: 'A55' },
						  { text: 'CASSETE 04', parentgroup: 'A22', align: 'center', name: 'A66' },
						  { text: 'REJECTED', parentgroup: 'A22', align: 'center', name: 'A77' },
						  { text: 'RETRACTS', parentgroup: 'A22', align: 'center', name: 'A88' },
						  { text: 'TOTAL RP.', parentgroup: 'A22', align: 'center', name: 'A99' },
						  
						  { text: 'PERHITUNGAN DISPENSED COUNTER', parentgroup: 'A1', align: 'center', name: 'AA22' },
						  { text: 'CASSETE 01', parentgroup: 'AA22', align: 'center', name: 'AA33' },
						  { text: 'TOTAL RP.', parentgroup: 'AA22', align: 'center', name: 'AA44' },
						  
						  { text: '', align: 'center', name: 'AB1' },
						  { text: 'PENGISIAN SELANJUTNYA', parentgroup: 'AB1', align: 'center', name: 'AB2' },
						  { text: 'TGL :', parentgroup: 'AB2', align: 'left', name: 'AB3' }
						]
					});
				});
			})( jq );
			
			function openFilter() {
				$("#html_content").toggle('slow');	
			}
		
    </script></head>
	<body class='default'>
		<div class="grid_14">
			<div id="html_content" hidden>
				<form class="form mysets-area">
					<table id="my_tables">
						<tr>
							<td>
								<p>
									<label for="simple-calendar">Bank</label>
								</p>
							</td>
							<td>
								<p>
									<select class="branch">
										<option> -Pilih Bank- </option>
									</select>
								</p>
							</td>
						</tr>
						<tr>
							<td>
								<p>
									<label for="simple-calendar">Tanggal</label>
								</p>
							</td>
							<td>
								<p>	
									<input type="text" name="simple-calendar" id="simple-calendar" value="" class="datepicker_search">
								</p>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div class="widget_top">
				<span class="h_icon list_images"></span>
				<h6>LAPORAN REKONSILIASI & PENGISIAN ATM</h6>
				<button style="float: right"><a href="<?=base_url()?>rekon_atm/export_rekon" style="color: white;"><span class="icon finished_work_sl"></span><span>EXPORT TO MS.EXCEL</span></a></button>
				<button style="float: right"><a href="#" onclick="openFilter()" style="color: white;"><span class="icon finished_work_sl"></span><span>FILTER</span></a></button>
			</div>
			<div class="block-border">
				<h1>Rekon ATM</h1>
				<div id="content_table1">
					<div>
						<?=$table?>
					</div>
				</div>
			</div>	
			<br><br>
			<div class="block-border">
				<h1>Rekon CDM</h1>
				<div id="content_table2">
					<div>
						<?=$table2?>
					</div>
				</div>
			</div>				
		</div>
	</body>
	</html>
	<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
	<script src="<?=base_url()?>depend/js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	<script src="<?=base_url()?>depend/js/full-calendar.jquery.js"></script>
	
	
	<script>
		jq341 = jQuery.noConflict(true);
		jq3412 = jQuery.noConflict(true);
		
		// jq341('#example').DataTable({
			// serverSide: true,
			// ajax: {
				// url: '<?=base_url()?>seal/server_processing',
				// dataFilter: function(data){
					// console.log(data);
					// var json = jQuery.parseJSON( data );
					// json.recordsTotal = json.recordsTotal;
					// json.recordsFiltered = json.recordsFiltered;
					// json.data = json.data;

					// return JSON.stringify( json ); // return JSON string
				// }
			// }
		// });
		
		// $('.dataTables_filter').addClass('pull-left');
		
			
		jq341( ".datepicker_search" ).datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			onClose: function(dateText, inst) { 
				// $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
				// alert(new Date(inst.selectedYear, inst.selectedMonth, 1));
				// alert(dateText);
				
				jq341('#content_table1 div').remove();
				jq341('#content_table2 div').remove();
				
				jq341.confirm({
					draggable: false,
					title: false,
					theme: 'light',
					content: "Please wait...",
					buttons: {
						yes: {
							isHidden: true, // hide the button
							keys: ['y'],
							action: function () {
								$.alert('Critical action <strong>was performed</strong>.');
							}
						}
					},
					onContentReady: function () {
						self = this;
						self.showLoading();
						
						$.ajax({
							url     : "<?=base_url()?>rekon_atm/get_data_by_search2",
							type    : "POST",
							data    : {date: dateText},
							dataType: "json",
							timeout : 10000,
							cache   : false,
							success : function(json){
								jq341('#content_table1').html(json.table1);
								jq341('#content_table2').html(json.table2);
								self.close();
							},
							error   : function(jqXHR, status, error){
								if(status==="timeout") {
									$.ajax(this);
									return;
								}
							}
						});
						
					}
				});
				
			}
		});  
		
		jq3412('.branch').select2({
			tags: false,
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'select/select_bank'?>',
				delay: 250,
				type: "POST",
				data: function(params) {
					return {
						search: params.term
					}
				},
				processResults: function (data, page) {
					console.log(data);
					return {
						results: data
					};
				}
			},
			maximumSelectionLength: 3,

			// add "(new tag)" for new tags
			createTag: function (params) {
			  var term = jq341.trim(params.term);

			  if (term === '') {
				return null;
			  }

			  return {
				id: term,
				text: term + ' (add new)'
			  };
			},
		});
	</script>

@endsection