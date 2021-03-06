@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>
	<!-- Always visible control bar -->
	
	<!-- End control bar -->

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
		
			<div class="block-border"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>CASH IN TRANSIT</h1>
				<br>
				<table class="table sortableXxx " cellspacing="0" width="100%">
				
					<thead>
						<tr>
							<th class="black-cell"><span class="loading"></span></th>
							<th scope="col">
								ID
							</th>
							<th scope="col">
								Combination Lock
							</th>
							<th scope="col">
								Status
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
							foreach($data_combination as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->wsid;?></td>
							<td>
								<?php if($session->userdata['level']=="LEVEL1") { ?>
								<?php echo $row->combination;?>
								<?php } else { ?>
								<?php echo str_repeat("*", strlen($row->combination));?>
								<?php } ?>
							</td>
							<td><?php echo $row->status;?></td>
							<?php if($session->userdata['level']=="LEVEL1") { ?>
								<td style="text-align: center">
									<button type="button" class='button green' onClick="window.location.href='<?php echo base_url();?>combination_lock/edit/<?php echo $row->id;?>'" title='Edit'><span class='smaller'>Edit</span></button>
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
	
		function openModalAdd()
		{
			
			var content = ''+
				'<p>'+
					'<label>Client</label>'+
					'<select name="cabang" class="js-example-basic-single2 full-width" required>'+
						'<option value="">- select cabang -</option>'+
					'</select>'+
				'</p>'+
				'<p>'+
					'<label>Combination Lock</label>'+
					'<input type="text" class="full-width" id="combi" value="" required>'+
				'</p>'+
				'<p>'+
					'<label>Status</label>'+
					'<select name="cabang" class="status_combination full-width" required>'+
						'<option value="">- select status -</option>'+
						'<option value="active"> ACTIVE </option>'+
						'<option value="inactive"> INACTIVE </option>'+
					'</select>'+
				'</p>'+
			'';

			$.modal({
				content: content,
				title: 'Input',
				maxWidth: 400,
				buttons: {
					'Yes': function(win) { 
						var wsid = jq341(".js-example-basic-single2 option:selected").val();
						var combi = jq341("#combi").val();
						var status = jq341(".status_combination option:selected").val();

						var data = {
							wsid: wsid,
							combi: combi,
							status: status
						};
					
						// alert(wsid+' '+combi+' '+status);
						$.ajax({
							url: '<?=base_url()?>combination_lock/save_data',
							dataType: 'html',
							type: 'POST',
							data: data,
							success: function(data) {
								if(data=="success") {
									window.location.reload();
								} else {
									alert("FAILED SAVE DATA");
								}
								// window.location.href = '<?=base_url()?>cashtransit/add/'+data;
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
					url: '<?php echo base_url().'select/select_client_wsid'?>',
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

			jq341('.status_combination').select2({
				width: '100%'
			});
		}
	
	</script>
@endsection