@extends('layouts.master')

@section('content')
	
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
													Branch
												</th>
												<th scope="col">
													Need to assign
												</th>
												<th scope="col">
													Action
												</th>
											</tr>
										</thead>
										
										<tbody>
											<?php 
												$no = 0;
												foreach($data_cashprocessing as $row): 
												$no++;
											?>
											<tr>
												<td class="th table-check-cell"><?=$no?></td>
												<td><?php echo $row->id_ct;?></td>
												<td><?php echo $row->date;?></td>
												<td><?php echo $row->name;?></td>
												<td><?php echo $row->count;?> Runsheets</td>
												<td style="text-align: center">
													<button type="button" class='button green' onClick="window.location.href='<?php echo base_url();?>cashprocessing/edit/<?php echo $row->id_ct;?>#tab-hcp'" title='Edit'><span class='smaller'>Detail</span></button>
												</td>
											</tr>
											<?php 
												endforeach; 
											?>
										</tbody>
									
									</table>
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
	
	<script>
		jq341 = jQuery.noConflict();
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
		
		function jsUcfirst(string) { return string.charAt(0).toUpperCase() + string.slice(1); }
		
		function openModalBranch()
		{
			// $.modal({
				// content: '<p><label>Branch</label><select name="cabang" class="js-example-basic-single2 full-width" required><option value="">- select cabang -</option></select></p>'+
						 // '<p><label>Zone</label><select name="cabang" class="js-example-basic-single2 full-width" required><option value="">- select cabang -</option></select></p>',
				// title: 'Input',
				// maxWidth: 400,
				// buttons: {
					// 'Yes': function(win) { 
						// $.ajax({
							// url: url,
							// dataType: 'html',
							// type: 'POST',
							// data: {id:id},
							// success: function(data) {
								// if(data=="success") {
									// window.location.reload();
								// } else {
									// win.closeModal();
								// }
							// }
						// });
					// },
					// 'Close': function(win) { win.closeModal(); }
				// }
			// });
			
			$.modal({
				content: '<p><label>Branch</label><select name="cabang" class="js-example-basic-single2 full-width" required><option value="">- select cabang -</option></select></p>',
				title: 'Input',
				maxWidth: 400,
				buttons: {
					'Yes': function(win) { 
						var id_branch = jq341(".js-example-basic-single2 option:selected").val();
					
						// alert(data);
						$.ajax({
							url: '<?=base_url()?>cashtransit/add_master',
							dataType: 'html',
							type: 'POST',
							data: {id:id_branch},
							success: function(data) {
								window.location.href = '<?=base_url()?>cashtransit/add/'+data;
							}
						});
					},
					'Close': function(win) { win.closeModal(); }
				}
			});
			
			jq341('.js-example-basic-single2').select2({
				tags: false,
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_branch'?>',
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
			
		}
	
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