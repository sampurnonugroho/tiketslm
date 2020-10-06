@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
			
	</div></div>
	<!-- End control bar -->

	<!-- Content -->
	<article class="container_12">
		<section class="grid_2"></section>
		<section class="grid_8">
			<div class="block-border">
				<form class="block-content form" enctype="multipart/form-data" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">
					<fieldset class="grey-bg required">
						<legend><?=ucwords(str_replace("_", " ", $active_menu))?> <?=ucfirst($flag)?></legend>
							<input type="hidden" class="full-width" name="id" value="<?=$id?>">
							<p>
								<label>ID Bank</label>
								<input type="text" class="full-width" name="wsid" value="<?=$wsid?>" required>
							</p>
							<p>
								<label>Bank</label>
								<select name="bank" class="handover_bank full-width" required>
									<option value="">- select bank -</option>
									<?php 
										if(isset($bank)) {
											echo '<option value="'.$bank.'" selected>'.$bank.'</option>';
										}
									?>
								</select>
							</p>
							<p>
								<label>Lokasi</label>
								<input type="text" class="full-width" name="lokasi" value="<?=$lokasi?>" required>
							</p>
							<p>
								<label>Tipe</label>
								<select name="type" class="full-width" required>
									<option value="">- select tipe -</option>
									<option value="atm" <?=($type=='atm'?"selected":"")?>> ATM </option>
									<option value="crm" <?=($type=='crm'?"selected":"")?>> CRM </option>
									<option value="cdm" <?=($type=='cdm'?"selected":"")?>> CDM </option>
								</select>
							</p>
							<p>
								<label>Jumlah Paket CR dan FLM</label>
								<input type="text" class="full-width" name="paket" value="<?=$paket?>" required>
							</p>
							<p>
								<label>Limit Minimum</label>
								<div style="width: 100%;">
									<input type="text" style="width: 48%; float: left" name="tgl_min_dari" placeholder="Dari Tanggal" value="<?=$tgl_min_dari?>" required>
									<input type="text" style="width: 47%; float: left" name="tgl_min_hingga" placeholder="Hingga Tanggal" value="<?=$tgl_min_hingga?>" required>
								</div>
								<input type="text" class="full-width" name="limit_min" placeholder="Nominal Limit" value="<?=$limit_min?>" required>
							</p>
							<p>
								<label>Limit Maximum</label>
								<div style="width: 100%;">
									<input type="text" style="width: 48%; float: left" name="tgl_max_dari" placeholder="Dari Tanggal" value="<?=$tgl_max_dari?>" required>
									<input type="text" style="width: 47%; float: left" name="tgl_max_hingga" placeholder="Hingga Tanggal" value="<?=$tgl_max_hingga?>" required>
								</div>
								<input type="text" class="full-width" name="limit_max" placeholder="Nominal Limit" value="<?=$limit_max?>" required>
							</p>
							<p>
								<label>Denominasi ATM</label>
								<input type="text" class="full-width" name="denom" value="<?=$denom?>" required>
							</p>
							<p>
								<label>Cartridge / Cassete</label>
								<input type="text" class="full-width" name="ctr" value="<?=$ctr?>" required>
							</p>
							<p>
								<label>Reject / Divert</label>
								<input type="text" class="full-width" name="reject" value="<?=$reject?>" required>
							</p>
							<p>
								<label>Interval</label>
								<input type="text" class="full-width" name="interval_isi" placeholder="VALUE INTERVAL TERAKHIR ISI" value="<?=$interval_isi?>" required>
							</p>
							<p>
								<label>Sifat</label>
								<select name="sifat" class="full-width" required>
									<option value="">- select tipe -</option>
									<option value="insidentil" <?=($sifat=='insidentil'?"selected":"")?>> Insidentil </option>
									<option value="permanen" <?=($sifat=='permanen'?"selected":"")?>> Permanent </option>
								</select>
							</p>
							<p>
								<label>Tanggal Efektif Layanan</label>
								<input type="text" class="full-width" name="tgl_efektif" value="<?=$tgl_efektif?>" required>
							</p>
							<p>
								<label>CUSTODY</label>
								<select name="custodian" class="full-width custodian_1" required>
									<option value="">- select custodi -</option>
									<?php 
										if(isset($custodian)) {
											echo '<option value="'.$custodian.'" selected>'.$nama_custodian.'</option>';
										}
									?>
								</select>
							</p>
						<button type="submit" style="float: right">Simpan</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
	</article>
	
	<script>
	
		jq341 = jQuery.noConflict(true);
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
		jq341(document).ready(function()
		{
			jq341('.handover_bank').select2({
				tags: true,
				tokenSeparators: [','],
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_bank_ho'?>',
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
			}).on('select2:select', function (evt) {
				var data = jq341(".handover_bank option:selected").text();
				// alert("Data yang dipilih adalah "+data);
				
				// jq341('.js-example-basic-single2').select2({
					// tags: true,
					// tokenSeparators: [','],
					// ajax: {
						// dataType: 'json',
						// url: '<?php echo base_url().'select/select_branch'?>',
						// delay: 250,
						// type: "POST",
						// data: function(params) {
							// return {
								// search: params.term,
								// bank: data,
							// }
						// },
						// processResults: function (data, page) {
							// console.log(data);
							// return {
								// results: data
							// };
						// }
					// },
					// maximumSelectionLength: 3,

					// // add "(new tag)" for new tags
					// createTag: function (params) {
					  // var term = jq341.trim(params.term);

					  // if (term === '') {
						// return null;
					  // }

					  // return {
						// id: term,
						// text: term + ' (add new)'
					  // };
					// },
				// });
			});
		
			jq341('.custodian_1').select2({
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'handover/suggest_custodi'?>',
					delay: 250,
					type: "POST",
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function (data, page) {
						return {
							results: data
						};
					}
				}
			}).on('select2:select', function (evt) {
				jq341(".custodian_2").val('').trigger('change')
			});
		});
	</script>
	
@endsection