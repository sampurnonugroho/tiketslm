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
		
    </script></head>
	<body class='default'>
		<div class="grid_14">
			<div class="widget_top">
				<span class="h_icon list_images"></span>
				<h6>LAPORAN REKONSILIASI & PENGISIAN ATM</h6>
				<button style="float: right"><a href="<?=base_url()?>rekon_atm/export_rekon" style="color: white;"><span class="icon finished_work_sl"></span><span>EXPORT TO MS.EXCEL</span></a></button>
			</div>
			<div class="block-border">
				<h1>Rekon ATM</h1>
				<?=$table?>
			</div>	
			<br><br>
			<div class="block-border">
				<h1>Rekon CDM</h1>
				<?=$table2?>
			</div>				
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