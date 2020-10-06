@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>


	<!-- Content -->
	<article class="container_12">
	<section class="grid_12">
			<div class="block-border">
			<div class="block-content no-title dark-bg">
				<div id="control-bar">
					<div class="container_16">
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION 
							
						</p>
					</div>
				</div>	
			</div>
		</div>
			<div class="block-border"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>Data Client - Zone</h1>
				<br>
				<div class="float-left">
					<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
				</div>
				<table class="table sortableXxx" cellspacing="0" width="100%">
				
					<thead>
						<tr>
							<th class="black-cell"><span class="loading"></span></th>
							<th scope="col">
								<span class="column-sort">
									<a href="javascript:void(0)" title="Sort up" class="sort-up"></a>
									<a href="javascript:void(0)" title="Sort down" class="sort-down"></a>
								</span>
								Branch Number
							</th>
							<th scope="col">
								Branch Name
							</th>
							<?php if($session->userdata['level']=="LEVEL1") { ?>
								<th scope="col">
									Aksi
								</th>
							<?php } ?>
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							foreach($data_branch as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->kode;?></td>
							<td><?php echo $row->name;?></td>
							<?php if($session->userdata['level']=="LEVEL1") { ?>
								<td style="text-align: center">
									<button type="button" class='button blue' onClick="window.location.href='<?php echo base_url();?>zone/add/<?php echo $row->id;?>'" title='Edit'><span class='smaller'>Detail</span></button>
								</td>
							<?php } ?>
						</tr>
						<?php 
							endforeach; 
						?>
					</tbody>
				
				</table>
					
			</form></div>
		</section>
		
		<div class="clear"></div>
		
	</article>
	
	<script>
		jq341 = jQuery.noConflict(true);
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
		
		function jsUcfirst(string) { return string.charAt(0).toUpperCase() + string.slice(1); }
	
		jq341(document).ready(function() {
			
			jq341('.sort_by_').select2().on('select2:select', function (evt) {
				// var data = jq341(".sort_by_ option:selected").text();
				var value = jq341(".sort_by_ option:selected").val();
				
				if(value!=="") {
					jq341('.sorted_by_1').show();
					jq341('#sort_title').html(jsUcfirst(value));
					
					proses_cari(value, function() {});
				} else {
					jq341('.sorted_by_1').hide();
				}
			});
			
			
			function proses_cari(value, calback) {
				console.log(value);
				var url;
				if(value=="bank") {
					url = '<?php echo base_url().'select/select_bank'?>';
				} else if(value=="branch") {
					url = '<?php echo base_url().'select/select_branch'?>';
				} else {
					url = '<?php echo base_url().'select/select_area'?>';
				}
				
				jq341('.sorted_by_').val([]);
				jq341('.sorted_by_').select2({
					val: "",
					tags: false,
					tokenSeparators: [','],
					ajax: {
						dataType: 'json',
						url: url,
						// delay: 250,
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
					maximumSelectionLength: 3
				}).on('select2:select', function (evt) {
					var data = jq341(".sorted_by_ option:selected").text();
					$.ajax({
						type: "POST",
						url : "<?php echo base_url().'select/getdataclient'?>",				
						data: {datas:data},
						success: function(msg){
							console.log(msg);
							var jsdata = JSON.parse(msg);	
							$('.sortableXxx').dataTable().fnClearTable();
							$('.sortableXxx').dataTable().fnAddData(jsdata).fnDraw();
						}
					});
				});
			}
			
			$('.sortableXxx').each(function(i)
			{
				// DataTable config
				var table = $(this),
					oTable = table.dataTable({
						/*
						 * We set specific options for each columns here. Some columns contain raw data to enable correct sorting, so we convert it for display
						 * @url http://www.datatables.net/usage/columns
						 */
						aoColumns: [
							{ bSortable: false },	// No sorting for this columns, as it only contains checkboxes
							{ sType: 'string' },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false }
						],
						
						/*
						 * Set DOM structure for table controls
						 * @url http://www.datatables.net/examples/basic_init/dom.html
						 */
						sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
						
						/*
						 * Callback to apply template setup
						 */
						fnDrawCallback: function()
						{
							this.parent().applyTemplateSetup();
						},
						fnInitComplete: function()
						{
							this.parent().applyTemplateSetup();
						}
					});
				
				// Sorting arrows behaviour
				table.find('thead .sort-up').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'asc']]);
					
					// Prevent bubbling
					return false;
				});
				table.find('thead .sort-down').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'desc']]);
					
					// Prevent bubbling
					return false;
				});
			});
		});
	</script>
@endsection