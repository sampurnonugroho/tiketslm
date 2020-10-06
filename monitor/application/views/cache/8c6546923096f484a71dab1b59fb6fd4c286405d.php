<?php $__env->startSection('content'); ?>
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
						<legend><?=strtoupper(str_replace("_", " ", $active_menu))?> <?=strtoupper($flag)?></legend>
							<input type="hidden" class="full-width" name="id" value="<?=$id?>">
							<?php
								if($newdata) { ?>
									<p>
										<label>Keterangan</label>
										<select name="keterangan" class="js-example-basic-single full-width" disabled>
											<option value="">- Select Keterangan -</option>
											<option value="saldo_awal" selected>SALDO AWAL</option>
										</select>
										<input type="hidden" class="full-width" id="debit_100" name="keterangan" value="saldo_awal" required>
									</p>
									<p>
										<label>DENOM 100</label>
										<input type="text" class="full-width" id="debit_100" name="debit_100" value="" required>
									</p>
									<p>
										<label>DENOM 50</label>
										<input type="text" class="full-width" id="debit_50" name="debit_50" value="" required>
									</p>
							<?php
								} else {
							?>
									<p>
										<label>Tanggal</label>
										<input type="text" class="full-width" name="tanggal" value="<?=$tanggal?>" required>
									</p>
									<p>
										<label>Catatan</label>
										<input type="text" class="full-width" name="catatan" value="<?=$catatan?>" required>
									</p>
									<p>
										<label>Keterangan</label>
										<select name="keterangan" class="js-example-basic-single full-width" required>
											<option value="">- Select Keterangan -</option>
											<option <?=($keterangan=="cash_supply" ? "selected" : "")?> value="cash_supply">CASH SUPPLY</option>
											<option <?=($keterangan=="return" ? "selected" : "")?> value="return">RETURN</option>
											<option <?=($keterangan=="replenish" ? "selected" : "")?> value="replenish">REPLENISH</option>
											<option <?=($keterangan=="delivery_return" ? "selected" : "")?> value="delivery_return">DELIVERY RETURN</option>
											<option <?=($keterangan=="tidak_ada_pengisian" ? "selected" : "")?> value="tidak_ada_pengisian">TIDAK ADA PENGISIAN</option>
											<option <?=($keterangan=="pembayaran_selisih" ? "selected" : "")?> value="pembayaran_selisih">PEMBAYARAN SELISIH</option>
											<option <?=($keterangan=="delivery_selisih" ? "selected" : "")?> value="delivery_selisih">DELIVERY SELISIH</option>
											<option <?=($keterangan=="pinjaman_cash_supply" ? "selected" : "")?> value="pinjaman_cash_supply">PINJAMAN CASH SUPPLY</option>
											<option <?=($keterangan=="pengembalian_cash_supply" ? "selected" : "")?> value="pengembalian_cash_supply">PENGEMBALIAN CASH SUPPLY</option>
											<option <?=($keterangan=="delivery_cash_supply" ? "selected" : "")?> value="delivery_cash_supply">DELIVERY CASH SUPPLY</option>

										</select>
									</p>
									<p>
										<label>Posisi</label>
										<select name="posisi" class="posisi full-width" required>
											<option value="">- Select debit/Kredit -</option>
											<option <?=($posisi=="debit" ? "selected" : "")?> value="debit">DEBIT</option>
											<option <?=($posisi=="kredit" ? "selected" : "")?> value="kredit">KREDIT</option>
										</select>
									</p>
									<div id="posisi_debit" hidden>
										<p>
											<label>DENOM 100</label>
											<input type="text" class="full-width" id="debit_100" name="debit_100" value="<?=$debit_100?>" >
										</p>
										<p>
											<label>DENOM 50</label>
											<input type="text" class="full-width" id="debit_50" name="debit_50" value="<?=$debit_50?>" >
										</p>
									</div>
									<div id="posisi_kredit" hidden>
										<p>
											<label>DENOM 100</label>
											<input type="text" class="full-width" id="kredit_100" name="kredit_100" value="<?=$kredit_100?>" >
										</p>
										<p>
											<label>DENOM 50</label>
											<input type="text" class="full-width" id="kredit_50" name="kredit_50" value="<?=$kredit_50?>" >
										</p>
									</div>
							<?php 
								}
							?>
						<button type="submit" style="float: right">Simpan</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
	</article>
	
	<script>
		$(document).on("change", ".posisi", function(that) {
			var that = $(this);
			
			// alert(that.val());
			
			if(that.val()=="debit") {
				$("#posisi_debit").show();
				$("#posisi_kredit").hide();
			} else if(that.val()=="kredit") {
				$("#posisi_debit").hide();
				$("#posisi_kredit").show();
			} else {
				$("#posisi_debit").hide();
				$("#posisi_kredit").hide();
			}
			
			$("#debit_100").val("");
			$("#debit_50").val("");
			$("#kredit_100").val("");
			$("#kredit_50").val("");
		});
		
		var that = $(".posisi");
		
		if(that.val()=="debit") {
			$("#posisi_debit").show();
			$("#posisi_kredit").hide();
		} else if(that.val()=="kredit") {
			$("#posisi_debit").hide();
			$("#posisi_kredit").show();
		} else {
			$("#posisi_debit").hide();
			$("#posisi_kredit").hide();
		}
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>