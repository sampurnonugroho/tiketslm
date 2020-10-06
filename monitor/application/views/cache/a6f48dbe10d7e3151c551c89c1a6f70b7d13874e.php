<?php $__env->startSection('content'); ?>
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
		
		#ui-datepicker-div {
			z-index: 99999999 !important;
		}
		.ui-datepicker {
			z-index: 99999999 !important;
		}
		.jconfirm {
			z-index: 99999998 !important;
		}
		
		input[readOnly] {
			background: #e6e6e6 !important;
		}
	</style>

	<!-- Content -->
	<article class="container_12">
		<section class="grid_12">
			<div class="block-border">
				<div class="block-content no-title dark-bg">
					<div id="control-bar">
						<div class="container_16">
							<div class="float-left">
								<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
							</div>
							
							<?php if($session->userdata['level']=="LEVEL1") { ?>
								<div class="float-right">
									<!--<button type="button" onClick="window.location.href='<?=base_url()?>cashreplenish/add'">Tambah</button>-->
									<button type="button" onClick="openModalBranch();">Tambah</button>
								</div>
							<?php } ?>
							<center>
								<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
							</center>
							<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION   
								<br>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?>]
							</p>
						</div>
					</div>	
				</div>
			</div>
		
			<div class="block-border">
				<form class="block-content form" id="table_form" method="post" action="#">
					<h1>CASH REPLENISH (H-<?=$h_min?>)</h1>
					<br>
					<div id="control-bar" class="clearfix">
						<div class="container_12">
							<div style="float: right">
								<label for="search">Search by Date : </label>
								<input type="text" name="simple-calendar" id="search" class="datepicker_search">
							</div>
						</div>
					</div>
					<div class="widget_content" id="content_table">
						<div>
							<table class="table" cellspacing="0" width="100%">
							
								<thead>
									<tr>
										<th class="black-cell"><span class="loading"></span></th>
										<th scope="col">
											Run Number
										</th>
										<th scope="col">
											Tanggal Dibuat
										</th>
										<th scope="col">
											Tanggal Eksekusi
										</th>
										<?php if($session->userdata['level']=="LEVEL1") { ?>
											<th scope="col">
												Action
											</th>
										<?php } ?>
									</tr>
								</thead>
								
								<tbody>
									<?php 
										$no = 0;
										if(count($data_cashreplenish)==0) {
											echo "<tr><td colspan='5' style='text-align: center'>NO DATA</td></tr>";
										}
										foreach($data_cashreplenish as $row): 
										$no++;
									?>
									<tr>
										<td class="th table-check-cell"><?=$no?></td>
										<td><?php echo $row->run_number;?></td>
										<td><?php echo date("d-m-Y", strtotime($row->date));?></td>
										<td><?php echo date("d-m-Y", strtotime($row->action_date));?></td>
										<?php if($session->userdata['level']=="LEVEL1") { ?>
											<td style="text-align: center">
												<button type="button" class='button green' onClick="window.location.href='<?php echo base_url();?>cashreplenish/edit_<?php echo $row->h_min; ?>/<?php echo $row->id_ct;?>'" title='Edit'><span class='smaller'>Detail</span></button>
												<button type="button" class='button red' onClick="openDelete('<?php echo $row->id_ct;?>', '<?php echo base_url();?>cashreplenish/delete')" title='Delete'><span class='smaller'>Delete</span></button>
												
											</td>
										<?php } ?>
									</tr>
									<?php 
										endforeach; 
									?>
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
	
	<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
	<script src="<?=base_url()?>depend/js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	<script src="<?=base_url()?>depend/js/full-calendar.jquery.js"></script>
	
	<script>
		jq341 = jQuery.noConflict(true);
		jq3412 = jQuery.noConflict(true);
		
		console.log(jq341().jquery);
		console.log(jq3412().jquery);
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq3412.fn.jquery + "<br>" );
		
		function jsUcfirst(string) { return string.charAt(0).toUpperCase() + string.slice(1); }
		
		jq341( ".datepicker_search" ).datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			onClose: function(dateText, inst) { 
				jq341('#content_table div').remove();
				
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
							url     : "<?=base_url()?>cashreplenish/get_table",
							type    : "POST",
							data    : {
								date: dateText,
								h_min: '<?=$h_min?>',
							},
							dataType: "html",
							timeout : 10000,
							cache   : false,
							success : function(json){
								jq341('#content_table').html(json);
								self.close();
							},
							error   : function(jqXHR, status, error){
								self.close();
								// alert(JSON.stringify(error));
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
	
		function openModalBranch()
		{
			var h_min = "<?=$h_min?>";
			var content = ''+
				'<form class="form">'+
					'<fieldset>'+
						'<p>'+
							'<label>Run Number</label>'+
							'<input type="text" id="run_number" placeholder="" value="<?=$run_number?>" readonly="readonly" class="full-width">'+
						'</p>'+
						'<p>'+
							'<label>Tanggal Action</label>'+
							'<input type="text" id="action_date" placeholder="" value="" class="full-width" required>'+
						'</p>'+
						'<p>'+
							'<label>Lokasi Kelolaan</label>'+
							'<select name="cabang" class="js-example-basic-single2 full-width" required>'+
								'<option value="">-- select kelolaan --</option>'+
							'</select>'+
						'</p>'+
					'</fieldset>'+
				'</form>'+
			'';
			
			$.modal({
				content: content,
				title: 'Input (H-<?=$h_min?>)',
				maxWidth: 400,
				buttons: {
					'Yes': function(win) { 
						var id_branch = jq3412(".js-example-basic-single2 option:selected").val();
						var run_number = jq3412("#run_number").val();
						var action_date = jq3412("#action_date").val();
						
						if(action_date=="") {
							alert("Mohon isi tanggal action!");
							
							return false;
						}
						if(id_branch=="") {
							alert("Mohon isi lokasi kelolaan!");
							
							return false;
						}
						
						alert("RUN NUMBER : "+run_number+" \nTANGGAL : "+action_date+" \nH MIN : "+h_min+" \nKELOLAAN : "+id_branch);
					
						// // alert(data);
						$.ajax({
							url: '<?=base_url()?>cashtransit/add_master',
							dataType: 'html',
							type: 'POST',
							data: {
								id:id_branch,
								h_min:h_min,
								action_date:action_date
							},
							success: function(data) {
								window.location.href = '<?=base_url()?>cashreplenish/edit_<?=$h_min?>/'+data;
							}
						});
					},
					'Close': function(win) { win.closeModal(); }
				}
			});
			
			if(h_min=="1") {
				$("#action_date").datepicker({
					minDate : +1,
					changeMonth: true,
					changeYear: true,
					showButtonPanel: false,
					dateFormat: 'dd-mm-yy',
					onClose: function(dateText, inst) { 
						console.log(inst);
					}
				});
			} else {
				$("#action_date").attr('readonly', true); 
				$("#action_date").val("<?=date('d-m-Y')?>");
			}	
			
			jq3412('.js-example-basic-single2').select2({
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
				  var term = jq3412.trim(params.term);

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
	
		jq3412(document).ready(function() {
			
			jq3412('.sort_by_').select2().on('select2:select', function (evt) {
				// var data = jq3412(".sort_by_ option:selected").text();
				var value = jq3412(".sort_by_ option:selected").val();
				
				if(value!=="") {
					jq3412('.sorted_by_1').show();
					jq3412('#sort_title').html(jsUcfirst(value));
					
					proses_cari(value, function() {});
				} else {
					jq3412('.sorted_by_1').hide();
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
				
				jq3412('.sorted_by_').val([]);
				jq3412('.sorted_by_').select2({
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
					var data = jq3412(".sorted_by_ option:selected").text();
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
						 * @url  http://www.datatables.net/usage/columns
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
						 * @url  http://www.datatables.net/examples/basic_init/dom.html
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>