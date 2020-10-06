@extends('layouts.master')

@section('content')
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<!--<link rel="stylesheet" href="<?=base_url()?>depend/JQW/jqwidgets/styles/jqx.base.css" type="text/css" />
		<link rel="stylesheet" href="<?=base_url()?>depend/JQW/jqwidgets/styles/jqx.classic.css" type="text/css" />
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/scripts/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxcore.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxbuttons.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxscrollbar.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxmenu.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxcheckbox.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxlistbox.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxdropdownlist.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.pager.js"></script>
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxgrid.selection.js"></script>	
		<script type="text/javascript" src="<?=base_url()?>depend/JQW/jqwidgets/jqxdata.js"></script>-->
		
		
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
					
					var source =
					{
						datatype: "xml",
						datafields: [
							 { name: 'NO', type: 'string' },
							 { name: 'ATM_ID', type: 'string' },
							 { name: 'LOKASI', type: 'string' },
							 { name: 'ATM_CRM', type: 'string' },
							 { name: 'TGL01', type: 'string' },
							 { name: 'JML_CSST01', type: 'string' },
							 { name: 'D50', type: 'string' },
							 { name: 'T50', type: 'string' },
							 { name: 'D100', type: 'string' },
							 { name: 'T100', type: 'string' },
							 { name: 'CSST1_50', type: 'string' },
							 { name: 'CSST1_100', type: 'string' },
							 { name: 'CSST2_50', type: 'string' },
							 { name: 'CSST2_100', type: 'string' },
							 { name: 'CSST3_50', type: 'string' },
							 { name: 'CSST3_100', type: 'string' },
							 { name: 'CSST4_50', type: 'string' },
							 { name: 'CSST4_100', type: 'string' },
							 { name: 'RJT50', type: 'string' },
							 { name: 'RJT100', type: 'string' },
							 { name: 'RTC50', type: 'string' },
							 { name: 'RTC100', type: 'string' },
							 { name: 'TOTALA', type: 'string' },
							 { name: 'CSST1B50', type: 'string' },
							 { name: 'CSST1B100', type: 'string' },
							 { name: 'TOTALB', type: 'string' },
							 { name: 'CCRM', type: 'string' },
							 { name: 'SELTOTAL', type: 'string' },
							 { name: 'TIME', type: 'string' },
							 { name: 'JML_CSST02', type: 'string' },
							 { name: 'D50B', type: 'string' },
							 { name: 'T50B', type: 'string' },
							 { name: 'D100B', type: 'string' },
							 { name: 'T100B', type: 'string' },
							 { name: 'KET_LIMIT', type: 'string' }
						], 
						url: '<?=base_url()?>depend/JQW/orderdetailsextended.xml',
						root: 'DATA',
						record: 'ROW'
					};
					var dataAdapter = new $.jqx.dataAdapter(source, {
						loadComplete: function () {
						}
					});
					// create grid.
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
						  { text: 'NO', cellsalign: 'center', align: 'center', datafield: 'NO', width: 50 },
						  { text: 'ATM ID', cellsalign: 'center', align: 'center', datafield: 'ATM_ID', width: 80 },
						  { text: 'LOKASI', cellsalign: 'center', align: 'center', datafield: 'LOKASI', width: 210 },
						  { text: 'ATM / CRM', cellsalign: 'center', align: 'center', datafield: 'ATM_CRM', width: 110 },
						  
						  { text: 'TANGGAL', columngroup: 'A2', datafield: 'TGL01', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 140 },
						  { text: 'JML CSST', columngroup: 'A3', datafield: 'JML_CSST01', cellsformat: 'd', cellsalign: 'center', align: 'center', width: 80 },
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
						  
						  { text: 'NEXT PLANNING', align: 'center', name: 'AB1' },
						  { text: 'PENGISIAN SELANJUTNYA', parentgroup: 'AB1', align: 'center', name: 'AB2' },
						  { text: 'TGL :', parentgroup: 'AB2', align: 'left', name: 'AB3' }
						]
					});
				});
			})( jq );
		
    </script></head>
	<body class='default'>
		 
		 <div class="grid_14">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_images"></span>
						<h6>LAPORAN REKONSILIASI & PENGISIAN ATM</h6>
					</div>
					<div class="widget_content">
						<div id="jqxgrid"></div>
					</div>
				</div>
			</div>
		<div class="grid_14">
		<div class="block-border">
				
                              <table class="table" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <td colspan="12"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/tick-circle.png" width="16" height="16" class="picto"> <b>SUMMARY INFORMATION</b> 
                                       </td>
                                    </tr>
                                 </thead>
                                 <thead>
                                    <tr>
                                       <th colspan="2" rowspan="8" style="white:black;font-size:22px;text-align: center;">JUMLAH LOKASI PENGISIAN</th>
                                       <td colspan="2" rowspan="8" style="white:black;font-size:22px;text-align: center;"><b>108</b></td>
									   <th colspan="8" style="text-align: center;">TOTAL PENGISIAN SEBELUMNYA </th>
                                    </tr>
                                    <tr>
                                       <th colspan="2">DENOM 50K</th>
                                       <th colspan="2">TOTAL</th>
									   <th colspan="2">DENOM 100K</th>
                                       <th colspan="2">TOTAL</th>
                                    </tr>
									<tr>
                                       <td colspan="2">DENOM 50K</td>
                                       <td colspan="2">DENOM 50K</td>
                                       <td colspan="2">DENOM 50K</td>
                                       <td colspan="2">DENOM 50K</td>
                                    </tr>
									</thead>
                                 
                              </table>
                              <table class="table" cellspacing="0" width="100%">
							  <thead>
                                    <tr>
                                       <td colspan="14"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/tick-circle.png" width="16" height="16" class="picto"> <b>PERHITUNGAN</b> 
                                       </td>
                                    </tr>
									<thead>
                                    <tr>
									   <th colspan="2" style="text-align: center;">CASSETE 01</th>
									   <th colspan="2" style="text-align: center;">CASSETE 02</th>
									   <th colspan="2" style="text-align: center;">CASSETE 03</th>
									   <th colspan="2" style="text-align: center;">CASSETE 04</th>
									   <th colspan="2" style="text-align: center;">REJECTED</th>
									   <th colspan="2" style="text-align: center;">RETRACTS</th>
									   <th colspan="2" style="text-align: center;">TOTAL</th>
                                    </tr>
                                    <tr>
                                       <th colspan="1">50K</th>
                                       <th colspan="1">100K</th>
									   <th colspan="1">50K</th>
                                       <th colspan="1">100K</th>
									   <th colspan="1">50K</th>
                                       <th colspan="1">100K</th>
									   <th colspan="1">50K</th>
                                       <th colspan="1">100K</th>
									   <th colspan="1">50K</th>
                                       <th colspan="1">100K</th>
									   <th colspan="1">50K</th>
                                       <th colspan="1">100K</th>
                                       <th colspan="1">(X1000)</th>
                                    </tr>
									</thead>
									
									</thead>
									<tbody>
									<tr>
										<td colspan="1">50K</td>
										<td colspan="1">100K</td>
										<td colspan="1">50K</td>
										<td colspan="1">100K</td>
										<td colspan="1">50K</td>
										<td colspan="1">100K</td>
										<td colspan="1">50K</td>
										<td colspan="1">100K</td>
										<td colspan="1">50K</td>
										<td colspan="1">100K</td>
										<td colspan="1">50K</td>
										<td colspan="1">100K</td>
										<td colspan="1">1000</td>
									</tr>
									</tbody>
                              </table>
							  <table class="table" cellspacing="0" width="100%">
							  <thead>
                                    <tr>
                                       <td colspan="14"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/tick-circle.png" width="16" height="16" class="picto"> <b>PERHITUNGAN DISPENSED COUNTER</b> 
                                       </td>
									  
                                    </tr>
									<thead>
                                    <tr>
									   <th colspan="8" style="text-align: center;">DENOMINASI</th>
									   <th colspan="2" rowspan="4" style="text-align: center;">COUNTER (CRM x1000)</th>
									   <th colspan="2" rowspan="4" style="text-align: center;">SELISIH TOTAL (x1000)</th>
                                    </tr>
                                    <tr>
                                       <th colspan="2">DENOM 50K</th>
									   <th colspan="2">TOTAL</th>
                                       <th colspan="2">DENOM 100K</th>
                                       <th colspan="2">TOTAL</th>
                                    </tr>
									</thead>
									
									</thead>
									<tbody>
									<tr>
										<td colspan="2">50K</td>
										<td colspan="2">100K</td>
										<td colspan="2">50K</td>
										<td colspan="2">100K</td>
										<td colspan="2">100K</td>
										<td colspan="2">100K</td>
									</tr>
									</tbody>
                              </table>
							  <table class="table" cellspacing="0" width="100%">
							  <thead>
                                    <tr>
                                       <td colspan="14"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/tick-circle.png" width="16" height="16" class="picto"> <b>PENGISIAN SELANJUTNYA</b> 
                                       </td>
									  
                                    </tr>
									<thead>
                                    <tr>
									   <th colspan="8" style="text-align: center;">DENOMINASI</th>
									   <th colspan="1" rowspan="6" style="text-align: center;">
									   <div class="btn_40_blue ucase">
									   <a href="<?=base_url()?>rekon_atm/export_rekon_atm"><span class="icon finished_work_sl"></span><span>EXPORT TO MS.EXCEL</span></a>
									   </div>
									   <div class="btn_40_blue ucase">
									   <a href="#"><span class="icon finished_work_sl"></span><span>EXPORT TO PDF</span></a>
									   </div>
							</th>
                                    </tr>
                                    <tr>
                                       <th colspan="2">DENOM 50K</th>
									   <th colspan="2">TOTAL</th>
                                       <th colspan="2">DENOM 100K</th>
                                       <th colspan="2">TOTAL</th>
                                    </tr>
									</thead>
									
									</thead>
									<tbody>
									<tr>
										<td colspan="2">50K</td>
										<td colspan="2">100K</td>
										<td colspan="2">50K</td>
										<td colspan="2">100K</td>
										<td colspan="2">TANGGAL : </td>
									</tr>
									</tbody>
									
                                 <tfoot>
                                    <tr>
                                       <td colspan="12"></td>
                                    </tr>
                                 </tfoot>
                              </table>
							  
                           </div></div>
						   <br>
								
		</div>
	</body>
	</html>
	<!--serverpaging_data.php
		#Include the connect.php file
		include('connect.php');
		#Connect to the database
		//connection String
		$connect = mysql_connect($hostname, $username, $password)
		or die('Could not connect: ' . mysql_error());
		//Select The database
		$bool = mysql_select_db($database, $connect);
		if ($bool === False){
		   print "can't find $database";
		}
		// get data and store in a json array

		$pagenum = $_GET['pagenum'];
		$pagesize = $_GET['pagesize'];
		$start = $pagenum * $pagesize;
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM Customers LIMIT $start, $pagesize";

		$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
		$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = mysql_query($sql);
		$rows = mysql_fetch_assoc($rows);
		$total_rows = $rows['found_rows'];
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$customers[] = array(
				'CompanyName' => $row['CompanyName'],
				'ContactName' => $row['ContactName'],
				'ContactTitle' => $row['ContactTitle'],
				'Address' => $row['Address'],
				'City' => $row['City'],
				'Country' => $row['Country']
			  );
		}
		$data[] = array(
		   'TotalRows' => $total_rows,
		   'Rows' => $customers
		);
		echo json_encode($data);

	-->

@endsection