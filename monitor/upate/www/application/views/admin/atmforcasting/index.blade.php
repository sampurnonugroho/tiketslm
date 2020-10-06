@extends('layouts.master')

@section('content')
	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
		
		<div class="float-right">
			<button type="button" onClick="window.location.href='<?=base_url()?>atmforcasting/add'">Tambah</button>
		</div>
			
	</div></div>
	<!-- End control bar -->

	<!-- Content -->
	<article class="container_12">
	<section class="grid_12">
			<div class="block-border"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>Sortable table</h1>
				
				<table class="table sortableXxx no-margin" cellspacing="0" width="100%">
				
					<thead>
						<tr>
							<th class="black-cell"><span class="loading"></span></th>
							<th scope="col">
								ID
							</th>
							<th scope="col">
								Date
							</th>
							<th scope="col">
								Action
							</th>
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							foreach($data_atmforcasting as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->id;?></td>
							<td><?php echo $row->date;?></td>
							<td style="text-align: center">
								<button type="button" class='button green' onClick="window.location.href='<?php echo base_url();?>atmforcasting/edit/<?php echo $row->id;?>'" title='Edit'><span class='smaller'>Detail</span></button>
								<button type="button" class='button red' onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>atmforcasting/delete')" title='Delete'><span class='smaller'>Delete</span></button>
							</td>
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
		jq341 = jQuery.noConflict();
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