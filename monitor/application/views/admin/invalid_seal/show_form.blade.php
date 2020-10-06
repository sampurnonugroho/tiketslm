<?php 
	$scanner_active = true;
?> 

<script type="text/javascript" src="<?=base_url()?>assets/jquery.scannerdetection.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/notify.min.js"></script>
<style>
	fieldset{margin-bottom: 1em;width:300px;}
	#suggest{position:absolute;z-index:5;border-left:silver 1px solid;padding:0 0 0 10px;background-color:#ebebeb}
	span.pilihan{display:block;cursor:pointer;padding:5px}
	
	.easyui-validatebox {
		margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 28px; line-height: 28px; width: 146px;
	}
	/* Customize the label (the container) */
	.container {
		display: block;
		position: relative;
		padding-left: 35px;
		margin-bottom: 12px;
		cursor: pointer;
		font-size: 22px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	/* Hide the browser's default checkbox */
	.container input {
		position: absolute;
		opacity: 0;
		cursor: pointer;
		height: 0;
		width: 0;
	}

	/* Create a custom checkbox */
	.checkmark {
		position: absolute;
		top: -2px;
		left: 8px;
		width: 16px;
		height: 16px;
		
		border: 2px solid #ffab3f;
		-moz-border-radius: 5px 5px 5px 5px;
		-webkit-border-radius: 5px 5px 5px 5px;
		border-radius: 5px 5px 5px 5px;
	}

	/* On mouse-over, add a grey background color */
	.container:hover input ~ .checkmark {
		background-color: #ccc;
	}

	/* When the checkbox is checked, add a blue background */
	.container input:checked ~ .checkmark {
		background-color: #ffab3f;
	}

	/* Create the checkmark/indicator (hidden when not checked) */
	.checkmark:after {
		content: "";
		position: absolute;
		display: none;
	}

	/* Show the checkmark when checked */
	.container input:checked ~ .checkmark:after {
		display: block;
	}

	/* Style the checkmark/indicator */
	.container .checkmark:after {
		left: 5px;
		top: 1px;
		width: 5px;
		height: 10px;
		border: solid white;
		border-width: 0 2px 2px 0;
		-webkit-transform: rotate(45deg);
		-ms-transform: rotate(45deg);
		transform: rotate(45deg);
	} 
</style>
<form method="post">
	<div>
		<?php if($state=="ro_cit"): ?>
			
			<?php 
				if(!empty($row->detail_uang)) {
			?>
					<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
						<tr>
							<td colspan="3" style="text-align: center">UANG KERTAS</td>
						</tr>
						<tr>
							<td>100.000</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="state" type="hidden" value="<?=$state?>" readonly>
								<input name="id" type="hidden" value="<?=$id?>" readonly>
								<input name="id_cashtransit" type="hidden" value="<?=$id_ct?>" readonly>
								<input name="runsheet" type="hidden" readonly>
								<input name="kertas_100k" id="pcs_100000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<tr>
							<td>50.000</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="kertas_50k" id="pcs_50000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<tr>
							<td>20.000</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="kertas_20k" id="pcs_20000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<tr>
							<td>10.000</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="kertas_10k" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<tr>
							<td>5.000</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="kertas_5k" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<tr>
							<td>2.000</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="kertas_2k" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<tr>
							<td>1.000</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="kertas_1k" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
					</table>
					<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
						<tr>
							<td colspan="3" style="text-align: center">UANG LOGAM</td>
						</tr>
						<tr>
							<td>1.000</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="logam_1k" id="pcs_5000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<tr>
							<td>500</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="logam_500" id="pcs_2000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>	
						</tr>
						<tr>
							<td>200</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="logam_200" id="pcs_1000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<tr>
							<td>100</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="logam_100" id="pcs_coin_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
					</table>
					<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
						<tr>
							<td>TOTAL</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="total" id="nama_lokasi" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr>
						<!-- <tr>
							<td>REAL NOMINAL</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="nominal" id="real_nominal_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
							</td>
						</tr> -->
						<tr>
							<td>BAG SEAL</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="bag_seal" id="bag_seal_cit_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input>
							</td>
						</tr>
						<tr>
							<td>BAG NO</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px">
								<input name="bag_no" id="bag_no_cit_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input>
							</td>
						</tr>	
						<tr>
							<td></td>
							<td></td>
							<td>
								<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="save_cit(this)">Save</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel_cit(this)">Cancel</a>
							</td>
						</tr>
					</table>
			<?php 
				}
			?>
			<script>
				<?php if($scanner_active) { ?>
				var element;
				jq341(document).scannerDetection({
					timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
					avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
					preventDefault: true,
					endChar: [13],
					onComplete: function(barcode, qty) {
						validScan = true;
						
						if(barcode.indexOf(".") != -1){
							$("#bag_no_cit_<?=$index?>").textbox('setValue', barcode);
						}
						if(barcode.indexOf("A") != -1){
							// $("#bag_seal_cdm_<?=$index?>").textbox('setValue', barcode);
							$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
								.done(function( data ) {
									console.log(data);
									if(data==-1) {
										jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
									} else {
										if(data==0) {
											jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
										} else {
											jq341.notify("Seal "+barcode+", siap digunakan!", "success");
											$("#bag_seal_cit_<?=$index?>").textbox('setValue', barcode);
										}
									}
								}
							);
						}
					},
					onError: function(string, qty) {
						if(element=="bag_seal") {
							$("#bag_seal_cit_<?=$index?>").textbox('setValue', string);
						} else {
							$("#bag_no_cit_<?=$index?>").textbox('setValue', string);	
						}
						$(":input").blur();
						element="";
					}
				});
				
				
				$(':input').live('focus', function(){
					console.log($(this));
					console.log($(this)[0]);
					console.log($(this)[0].nextElementSibling);
					console.log($(this)[0].nextElementSibling.name);
					
					element = $(this)[0].nextElementSibling.name;
					
					if($("#"+element+"_atm_<?=$index?>").val()!=="") {
						$("#"+element+"_atm_<?=$index?>").textbox('setValue', '');
					}
				});
				<?php } ?>
			</script>
		<?php elseif($state=="ro_atm"): ?>
			<?php 
				$r = get_object_vars($row);
				
				if($r['act']=="ATM") {
			?>
					<input name="act" type="hidden" value="<?=$r['act']?>" readonly>
					<input name="state" type="hidden" value="<?=$state?>" readonly>
					<input name="id" type="hidden" value="<?=$id?>" readonly>
					<input name="id_cashtransit" type="hidden" value="<?=$id_ct?>" readonly>
					<input name="runsheet" type="hidden" readonly>
					<table class="dv-table1_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
						
					</table>
					<table class="dv-table2_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
						
					</table>
					<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
						<tr>
							<td>CART</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_seal" style="height: 28px" class="easyui-validatebox easyui-textbox cart_total" disabled="disabled"></input></td>
						</tr>
						<tr>
							<td>DIV</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="divert" id="divert_atm_<?=$index?>" style="height: 28px" value="<?=$r['divert']?>" class="easyui-validatebox tb"></input></td>
						</tr>
						<tr>
							<td>BAG SEAL</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_seal" id="bag_seal_atm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
						</tr>
						<tr>
							<td>BAG NO</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_no" id="bag_no_atm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
						</tr>	
						<tr>
							<td>T-BAG</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="t_bag" id="tbag_atm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input></td>
						</tr>	
						<tr <?=($row->denom!="100000" ? "style='display: none'" : "")?>>
							<td>100K</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="s100k" id="s100k_atm_<?=$index?>" style="height: 28px" class="easyui-validatebox tb"></input></td>
						</tr>
						<tr <?=($row->denom!="50000" ? "style='display: none'" : "")?>>
							<td>50K</td>
							<td>:</td>
							<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="s50k" id="s50k_atm_<?=$index?>" style="height: 28px" class="easyui-validatebox tb"></input></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>
								<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="save1(this)">Save</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel1(this)">Cancel</a>
							</td>
						</tr>
					</table>
					
					<script>
						<?php if($scanner_active) { ?>
						
						var element;
						jq341(document).scannerDetection({
							timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
							avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
							preventDefault: true,
							endChar: [13],
							onComplete: function(barcode, qty) {
								validScan = true;
								// alert(barcode);
								
								if(barcode.indexOf("BJK") != -1){
									$("#tbag_atm_<?=$index?>").textbox('setValue', barcode);
								}
								if(barcode.indexOf(".") != -1){
									// $("#bag_no_atm_<?=$index?>").textbox('setValue', barcode);
									$.post('<?php echo base_url().'cashprocessing/check_big_seal'?>', { value: barcode, id_bank: "<?=$row->id_bank?>", id: "<?=$row->id?>", ctr: "<?=$row->ctr?>", act: "<?=$row->act?>" })
										.done(function( data ) {
											console.log(data);
											if(data=="") {
												$("#bag_no_atm_<?=$index?>").textbox('setValue', barcode);
											} else {
												jq341.notify(data, "error");
											}
										}
									);
								}
								if(barcode.indexOf("A") != -1){
									// $("#bag_seal_atm_<?=$index?>").textbox('setValue', barcode);
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
										.done(function( data ) {
											console.log(data);
											if(data==-1) {
												jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
											} else {
												if(data==0) {
													jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
												} else {
													jq341.notify("Seal "+barcode+", siap digunakan!", "success");
													$("#bag_seal_atm_<?=$index?>").textbox('setValue', barcode);
												}
											}
										}
									);
								}
								if(barcode.indexOf("a") != -1){
									if($("#cart_1_seal_atm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_atm_<?=$index?>").val()!==barcode && 
											$("#divert_atm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_1_seal_atm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_2_seal_atm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_atm_<?=$index?>").val()!==barcode && 
											$("#divert_atm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_2_seal_atm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_3_seal_atm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_atm_<?=$index?>").val()!==barcode && 
											$("#divert_atm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_3_seal_atm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_4_seal_atm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_atm_<?=$index?>").val()!==barcode && 
											$("#divert_atm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_4_seal_atm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#divert_atm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_atm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_atm_<?=$index?>").val()!==barcode && 
											$("#divert_atm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#divert_atm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									}
								}
								
								$(":input").blur();
								element="";
							},
							onError: function(string, qty) {
								// alert(qty);
								if(element=="s50k") {
									$("#s50k_atm_<?=$index?>").textbox('setValue', string);
								} else if(element=="s100k") {
									$("#s100k_atm_<?=$index?>").textbox('setValue', string);
								}
								
								$(":input").blur();
								element="";
							}
						});
						
						$(':input').live('focus', function(){
							// console.log($(this));
							// console.log($(this)[0]);
							// console.log($(this)[0].nextElementSibling);
							// console.log($(this)[0].nextElementSibling.name);
							
							element = $(this)[0].nextElementSibling.name;
							
							if($("#"+element+"_atm_<?=$index?>").val()!=="") {
								$("#"+element+"_atm_<?=$index?>").textbox('setValue', '');
							}
						});
						<?php } ?>
					
						jq341(document).ready(function() {
							$('table.dv-table1_<?=$index?> tr').remove();
							$('table.dv-table2_<?=$index?> tr').remove();
							var cart = parseInt("<?=$row->ctr?>");
							var row1 = '';
							var row2 = '';
							
							
							for(var i=1; i<=cart; i++) {
								if(i==1) {
									cart_no = "<?=$r['cart_1_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_1_seal = "<?=$r['cart_1_seal']?>";
									// alert(cart_1_seal);
									
									row1 +=   '<tr>'+
										  '<td style="border: 0px">CART '+i+' NO</td>'+
										  '<td style="border: 0px">:</td>'+
										  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										  '</tr>';
										  
									row2 +=   '<tr>'+
										  '<td style="border: 0px">CART '+i+' SEAL</td>'+
										  '<td style="border: 0px">:</td>'+
										  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_atm_<?=$index?>" style="height: 28px" value="'+cart_1_seal+'"  class="easyui-validatebox tb easyui-textbox" type="text"></td>'+
										  '</tr>';
									
								}
								if(i==2) {
									cart_no = "<?=$r['cart_2_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_2_seal = "<?=$r['cart_2_seal']?>";
									
									row1 +=   '<tr>'+
										  '<td style="border: 0px">CART '+i+' NO</td>'+
										  '<td style="border: 0px">:</td>'+
										  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										  '</tr>';
										  
									row2 +=   '<tr>'+
										  '<td style="border: 0px">CART '+i+' SEAL</td>'+
										  '<td style="border: 0px">:</td>'+
										  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_atm_<?=$index?>" style="height: 28px" value="'+cart_2_seal+'"  class="easyui-validatebox tb easyui-textbox" type="text"></td>'+
										  '</tr>';
								}
								if(i==3) {
									cart_no = "<?=$r['cart_3_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_3_seal = "<?=$r['cart_3_seal']?>";
									
									row1 +=   '<tr>'+
										  '<td style="border: 0px">CART '+i+' NO</td>'+
										  '<td style="border: 0px">:</td>'+
										  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										  '</tr>';
										  
									row2 +=   '<tr>'+
										  '<td style="border: 0px">CART '+i+' SEAL</td>'+
										  '<td style="border: 0px">:</td>'+
										  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_atm_<?=$index?>" style="height: 28px" value="'+cart_3_seal+'"  class="easyui-validatebox tb easyui-textbox" type="text"></td>'+
										  '</tr>';
								}
								if(i==4) {
									cart_no = "<?=$r['cart_4_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_4_seal = "<?=$r['cart_4_seal']?>";
									
									row1 +=   '<tr>'+
										  '<td style="border: 0px">CART '+i+' NO</td>'+
										  '<td style="border: 0px">:</td>'+
										  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										  '</tr>';
										  
									row2 +=   '<tr>'+
										  '<td style="border: 0px">CART '+i+' SEAL</td>'+
										  '<td style="border: 0px">:</td>'+
										  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_atm_<?=$index?>" style="height: 28px" value="'+cart_4_seal+'"  class="easyui-validatebox tb easyui-textbox" type="text"></td>'+
										  '</tr>';
								}
								
							}
							
							$(".cart_total").textbox('setValue', cart);
							
							$('table.dv-table1_<?=$index?>').append(row1);
							$('table.dv-table2_<?=$index?>').append(row2);
							
							$('.tb').textbox();
							
							// var t = $('#bag_seal');
							// var typed_into;
							// t.textbox('textbox').bind({
								// keypress: function() { typed_into = true; },
								// change: function(e) {
									// if (typed_into) {
										// // alert('Use Scanner');
										// // t.textbox('setValue', '');
										// typed_into = false; //reset type listener
									// } else {
										// alert('not type');
									// }
								// }
							// });
							
							var seal_1 = $("#cart_1_seal");
							var seal_2 = $("#cart_2_seal");
							var seal_3 = $("#cart_3_seal");
							var seal_4 = $("#cart_4_seal");
							seal_1.textbox('textbox').bind({
								change: function(e) {
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: seal_1.val() })
										.done(function( data ) {
											if(data>0) {
												
											} else {
												alert("SEAL INVALID");
												seal_1.textbox('setValue', '');
											}
										}
									);
								}
							});
							seal_2.textbox('textbox').bind({
								change: function(e) {
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: seal_2.val() })
										.done(function( data ) {
											if(data>0) {
												
											} else {
												alert("SEAL INVALID");
												seal_2.textbox('setValue', '');
											}
										}
									);
								}
							});
							seal_3.textbox('textbox').bind({
								change: function(e) {
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: seal_3.val() })
										.done(function( data ) {
											if(data>0) {
												
											} else {
												alert("SEAL INVALID");
												seal_3.textbox('setValue', '');
											}
										}
									);
								}
							});
							seal_4.textbox('textbox').bind({
								change: function(e) {
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: seal_4.val() })
										.done(function( data ) {
											if(data>0) {
												
											} else {
												alert("SEAL INVALID");
												seal_4.textbox('setValue', '');
											}
										}
									);
								}
							});
							
							var divert = $("#divert");
							var bag_seal = $("#bag_seal");
							var bag_no = $("#bag_no");
							divert.textbox('textbox').bind({
								// change: function(e) {
									// $.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: divert.val() })
										// .done(function( data ) {
											// if(data>0) {
												// bag_seal.textbox('textbox').focus();
											// } else {
												// alert("SEAL INVALID");
												// divert.textbox('setValue', '');
											// }
										// }
									// );
								// }
								keypress: function() { typed_into = true; },
								change: function() {
									if (typed_into) {
										alert('type');
										typed_into = false; //reset type listener
									} else {
										alert('not type');
									}
								}
							});
							bag_seal.textbox('textbox').bind({
								change: function(e) {
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: bag_seal.val() })
										.done(function( data ) {
											if(data>0) {
												bag_no.textbox('textbox').focus();
											} else {
												alert("SEAL INVALID");
												bag_seal.textbox('setValue', '');
											}
										}
									);
								}
							});
							bag_no.textbox('textbox').bind({
								change: function(e) {
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: bag_no.val() })
										.done(function( data ) {
											if(data>0) {
												
											} else {
												alert("SEAL INVALID");
												bag_no.textbox('setValue', '');
											}
										}
									);
								}
							});
						});
						
						function functionPress(val) {
							val.value = val.value;
						}
					</script>
			<?php 
				} else if($r['act']=="CRM") {
					// echo "<pre>";
					// print_r($row->ctr);
			?>
					<div>
						<input name="act" type="hidden" value="<?=$r['act']?>" readonly>
						<input name="state" type="hidden" value="<?=$state?>" readonly>
						<input name="id" type="hidden" value="<?=$id?>" readonly>
						<input name="id_cashtransit" type="hidden" value="<?=$id_ct?>" readonly>
						<input name="runsheet" type="hidden" readonly>
						<table class="dv-table1_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
							
						</table>
						<table class="dv-table2_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
							
						</table>
						<table class="dv-table3_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
							
						</table>
						<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
							<tr>
								<td>CART</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_seal" style="height: 28px" class="easyui-validatebox easyui-textbox cart_total" disabled="disabled"></input></td>
							</tr>
							<tr>
								<td>DIV</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="divert" id="divert_crm_<?=$index?>" style="height: 28px" value="<?=$r['divert']?>" class="easyui-validatebox tb" required="true"></input></td>
							</tr>
							<tr>
								<td>BAG SEAL</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_seal" id="bag_seal_crm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
							</tr>
							<tr>
								<td>BAG NO</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_no" id="bag_no_crm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
							</tr>
							<tr>
								<td>T-BAG</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="t_bag" id="tbag_crm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input></td>
							</tr>	
							<tr <?=($row->denom!="100000" ? "style='display: none'" : "")?>>
								<td>100K</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="s100k" style="height: 28px" class="easyui-validatebox tb"></input></td>
							</tr>
							<tr <?=($row->denom!="50000" ? "style='display: none'" : "")?>>
								<td>50K</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="s50k" style="height: 28px" class="easyui-validatebox tb"></input></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td>
									<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="save1(this)">Save</a>
									<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel1(this)">Cancel</a>
								</td>
							</tr>
						</table>
					</div>
					
					<script>
						<?php if($scanner_active) { ?>
						var element;
						jq341(document).scannerDetection({
							timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
							avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
							preventDefault: true,
							endChar: [13],
							onComplete: function(barcode, qty) {
								validScan = true;
								// alert(barcode);
								
								if(barcode.indexOf("BJK") != -1){
									$("#tbag_crm_<?=$index?>").textbox('setValue', barcode);
								}
								if(barcode.indexOf(".") != -1){
									$("#bag_no_crm_<?=$index?>").textbox('setValue', barcode);
								}
								if(barcode.indexOf("A") != -1){
									// $("#bag_seal_crm_<?=$index?>").textbox('setValue', barcode);
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
										.done(function( data ) {
											console.log(data);
											if(data==-1) {
												jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
											} else {
												if(data==0) {
													jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
												} else {
													jq341.notify("Seal "+barcode+", siap digunakan!", "success");
													$("#bag_seal_crm_<?=$index?>").textbox('setValue', barcode);
												}
											}
										}
									);
								}
								if(barcode.indexOf("a") != -1){
									if($("#cart_1_seal_crm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_crm_<?=$index?>").val()!==barcode && 
											$("#divert_crm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_1_seal_crm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_2_seal_crm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_crm_<?=$index?>").val()!==barcode && 
											$("#divert_crm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_2_seal_crm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_3_seal_crm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_crm_<?=$index?>").val()!==barcode && 
											$("#divert_crm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_3_seal_crm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_4_seal_crm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_crm_<?=$index?>").val()!==barcode && 
											$("#divert_crm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_4_seal_crm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_5_seal_crm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_crm_<?=$index?>").val()!==barcode && 
											$("#divert_crm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_5_seal_crm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#divert_crm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_crm_<?=$index?>").val()!==barcode && 
											$("#divert_crm_<?=$index?>").val()!==barcode) {
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#divert_crm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									}
								}
								
								$(":input").blur();
								element="";
							},
							onError: function(string, qty) {
								// alert(qty);
								if(element=="value_1") {
									$("#value_1_crm_<?=$index?>").textbox('setValue', string);
								} else if(element=="value_2") {
									$("#value_2_crm_<?=$index?>").textbox('setValue', string);
								} else if(element=="value_3") {
									$("#value_3_crm_<?=$index?>").textbox('setValue', string);
								} else if(element=="value_4") {
									$("#value_4_crm_<?=$index?>").textbox('setValue', string);
								}
								
								$(":input").blur();
								element="";
							}
						});
						
						$(':input').live('focus', function(){
							// console.log($(this));
							// console.log($(this)[0]);
							// console.log($(this)[0].nextElementSibling);
							// console.log($(this)[0].name);
							
							element = $(this)[0].nextElementSibling.name;
							if($("#"+element+"_crm_<?=$index?>").val()!=="") {
								$("#"+element+"_crm_<?=$index?>").textbox('setValue', '');
							}
						});
						<?php } ?>
					
						jq341(document).ready(function() {
							$('table.dv-table1_<?=$index?> tr').remove();
							$('table.dv-table2_<?=$index?> tr').remove();
							var cart = parseInt("<?=$row->ctr?>");
							var row1 = '';
							var row2 = '';
							var row3 = '';
							
							for(var i=1; i<=cart; i++) {
								if(i==1) {
									cart_no   = "<?=$r['cart_1_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_1_seal']?>";
									res = cart_seal.split(";");
									console.log(res);
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_crm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
									
									if(i!=cart) {		
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										var value = res[2] == undefined ? "" : res[2];
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 7px 15px 5px 0px">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" id="value_'+i+'_crm_<?=$index?>" value="'+value+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								} else if(i==2) {
									cart_no   = "<?=$r['cart_2_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_2_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_crm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
									
									if(i!=cart) {	
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										var value = res[2] == undefined ? "" : res[2];
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 5px 15px 5px 0px;">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" id="value_'+i+'_crm_<?=$index?>" value="'+value+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								} else if(i==3) {
									cart_no   = "<?=$r['cart_3_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_3_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_crm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
											
									if(i!=cart) {
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										var value = res[2] == undefined ? "" : res[2];
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 5px 15px 5px 0px;">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" id="value_'+i+'_crm_<?=$index?>" value="'+value+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								} else if(i==4) {
									cart_no   = "<?=$r['cart_4_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_4_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_crm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
											
									if(i!=cart) {
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										var value = res[2] == undefined ? "" : res[2];
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 5px 15px 5px 0px;">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" id="value_'+i+'_crm_<?=$index?>" value="'+value+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								} else if(i==5) {
									cart_no   = "<?=$r['cart_5_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_5_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_crm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
											
									if(i!=cart) {
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										var value = res[2] == undefined ? "" : res[2];
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 5px 15px 5px 0px;">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" id="value_'+i+'_crm_<?=$index?>" value="'+value+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								}
							}
							
							$(".cart_total").textbox('setValue', cart);
							
							$('table.dv-table1_<?=$index?>').append(row1);
							$('table.dv-table2_<?=$index?>').append(row2);
							$('table.dv-table3_<?=$index?>').append(row3);
							
							$('.tb').textbox();
						});
					</script>
			<?php 
				} else if($r['act']=="CDM") {
					// echo "<pre>";
					// print_r($row->ctr);
			?>
					<div>
						<input name="act" type="hidden" value="<?=$r['act']?>" readonly>
						<input name="state" type="hidden" value="<?=$state?>" readonly>
						<input name="id" type="hidden" value="<?=$id?>" readonly>
						<input name="id_cashtransit" type="hidden" value="<?=$id_ct?>" readonly>
						<input name="runsheet" type="hidden" readonly>
						<table class="dv-table1_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
							
						</table>
						<table class="dv-table2_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
							
						</table>
						<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
							<tr>
								<td>CART</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="" style="height: 28px" class="easyui-validatebox easyui-textbox cart_total" disabled="disabled"></input></td>
							</tr>
							<tr>
								<td>DIV</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="divert" id="divert_cdm_<?=$index?>" style="height: 28px" value="<?=$r['divert']?>" class="easyui-validatebox tb" required="true"></input></td>
							</tr>
							<tr>
								<td>BAG SEAL</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_seal" id="bag_seal_cdm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
							</tr>
							<tr>
								<td>BAG NO</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_no" id="bag_no_cdm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
							</tr>	
							<tr>
								<td>T-BAG</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="t_bag" id="tbag_cdm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input></td>
							</tr>	
							<tr <?=($row->denom!="100000" ? "style='display: none'" : "")?>>
								<td>100K</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="s100k" style="height: 28px" class="easyui-validatebox tb"></input></td>
							</tr>
							<tr <?=($row->denom!="50000" ? "style='display: none'" : "")?>>
								<td>50K</td>
								<td>:</td>
								<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="s50k" style="height: 28px" class="easyui-validatebox tb"></input></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td>
									<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="save1(this)">Save</a>
									<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel1(this)">Cancel</a>
								</td>
							</tr>
						</table>
					</div>
					
					<script>
						<?php if($scanner_active) { ?>
						var element;
						jq341(document).scannerDetection({
							timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
							avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
							preventDefault: true,
							endChar: [13],
							onComplete: function(barcode, qty) {
								validScan = true;
								
								if(barcode.indexOf("BJK") != -1){
									$("#tbag_cdm_<?=$index?>").textbox('setValue', barcode);
								}
								if(barcode.indexOf(".") != -1){
									$("#bag_no_cdm_<?=$index?>").textbox('setValue', barcode);
								}
								if(barcode.indexOf("A") != -1){
									// $("#bag_seal_cdm_<?=$index?>").textbox('setValue', barcode);
									$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
										.done(function( data ) {
											console.log(data);
											if(data==-1) {
												jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
											} else {
												if(data==0) {
													jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
												} else {
													jq341.notify("Seal "+barcode+", siap digunakan!", "success");
													$("#bag_seal_cdm_<?=$index?>").textbox('setValue', barcode);
												}
											}
										}
									);
								}
								if(barcode.indexOf("a") != -1){
									if($("#cart_1_seal_cdm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#divert_cdm_<?=$index?>").val()!==barcode) {
											// $("#cart_1_seal_cdm_<?=$index?>").textbox('setValue', barcode);
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_1_seal_cdm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_2_seal_cdm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#divert_cdm_<?=$index?>").val()!==barcode) {
											// $("#cart_2_seal_cdm_<?=$index?>").textbox('setValue', barcode);
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_2_seal_cdm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_3_seal_cdm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#divert_cdm_<?=$index?>").val()!==barcode) {
											// $("#cart_3_seal_cdm_<?=$index?>").textbox('setValue', barcode);
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_3_seal_cdm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_4_seal_cdm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#divert_cdm_<?=$index?>").val()!==barcode) {
											// $("#cart_4_seal_cdm_<?=$index?>").textbox('setValue', barcode);
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_4_seal_cdm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#cart_5_seal_cdm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#divert_cdm_<?=$index?>").val()!==barcode) {
											// $("#cart_5_seal_cdm_<?=$index?>").textbox('setValue', barcode);
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#cart_5_seal_cdm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} else if($("#divert_cdm_<?=$index?>").val()=="") {
										if($("#cart_1_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_2_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_3_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_4_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#cart_5_seal_cdm_<?=$index?>").val()!==barcode && 
											$("#divert_cdm_<?=$index?>").val()!==barcode) {
											// $("#divert_cdm_<?=$index?>").textbox('setValue', barcode);
											$.post('<?php echo base_url().'cashprocessing/check_seal'?>', { value: barcode })
												.done(function( data ) {
													console.log(data);
													if(data==-1) {
														jq341.notify("Seal "+barcode+", tidak dikenal!", "error");
													} else {
														if(data==0) {
															jq341.notify("Seal "+barcode+", sudah terpakai!", "error");
														} else {
															jq341.notify("Seal "+barcode+", siap digunakan!", "success");
															$("#divert_cdm_<?=$index?>").textbox('setValue', barcode);
														}
													}
												}
											);
										} else {
											jq341.notify(barcode+", sudah ada!", "error");
										}
									} 
								}
							},
							onError: function(string, qty) {
								console.log(element);
							}
						});
						
						$(':input').live('focus', function(){
							// console.log($(this));
							// console.log($(this)[0]);
							// console.log($(this)[0].nextElementSibling);
							// console.log($(this)[0].nextElementSibling.name);
							// console.log($(this)[0].name);
							
							element = $(this)[0].nextElementSibling.name;
							if($("#"+element+"_crm_<?=$index?>").val()!=="") {
								$("#"+element+"_crm_<?=$index?>").textbox('setValue', '');
							}
						});
						<?php } ?>
					
						jq341(document).ready(function() {
							$('table.dv-table1_<?=$index?> tr').remove();
							$('table.dv-table2_<?=$index?> tr').remove();
							var cart = parseInt("<?=$row->ctr?>");
							var row1 = '';
							var row2 = '';
							var row3 = '';
							
							for(var i=1; i<=cart; i++) {
								if(i==1) {
									cart_no   = "<?=$r['cart_1_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_1_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_cdm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
									
									if(i!=cart) {		
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 7px 15px 5px 0px">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" value="'+res[2]+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								} else if(i==2) {
									cart_no   = "<?=$r['cart_2_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_2_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_cdm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
									
									if(i!=cart) {	
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 5px 15px 5px 0px;">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" value="'+res[2]+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								} else if(i==3) {
									cart_no   = "<?=$r['cart_3_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_3_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_cdm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
											
									if(i!=cart) {
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 5px 15px 5px 0px;">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" value="'+res[2]+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								} else if(i==4) {
									cart_no   = "<?=$r['cart_4_no']?>";
									if(cart_no=="") {
										cart_no = i;
									}
									cart_seal = "<?=$r['cart_4_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_cdm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
											
									if(i!=cart) {
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 5px 15px 5px 0px;">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" value="'+res[2]+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								} else if(i==5) {
									cart_no   = "<?=$r['cart_5_no']?>";
									cart_seal = "<?=$r['cart_5_seal']?>";
									res = cart_seal.split(";");
									
									row1 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' NO</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_no" style="height: 28px;" value="'+cart_no+'" class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
										  
									row2 += '<tr>'+
										    '<td style="border: 0px">CART '+i+' SEAL</td>'+
										    '<td style="border: 0px">:</td>'+
										    '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" id="cart_'+i+'_seal_cdm_<?=$index?>" style="height: 28px" value="'+res[0]+'"  class="easyui-validatebox tb" type="text"></td>'+
										    '</tr>';
											
									if(i!=cart) {
										var val_50 = res[1] == "50" ? "selected" : "";
										var val_100 = res[1] == "100" ? "selected" : "";
										row3 += '<tr>'+
												'<td style="border: 0px; padding: 5px 15px 5px 0px;">'+
													'<select name="denom_'+i+'" class="easyui-combobox">'+
														'<option value="50" '+val_50+'>50K</option>'+
														'<option value="100" '+val_100+'>100K</option>'+
													'</select>'+
													' <input name="value_'+i+'" style="height: 28px" value="'+res[2]+'"  class="easyui-validatebox tb" type="text">'+
												'</td>'+
												'</tr>';
									}
								}
							}
							
							$(".cart_total").textbox('setValue', cart);
							
							$('table.dv-table1_<?=$index?>').append(row1);
							$('table.dv-table2_<?=$index?>').append(row2);
							$('table.dv-table3_<?=$index?>').append(row3);
							
							$('.tb').textbox();
						});
					</script>
			<?php 
				} 
			?>
		<?php endif; ?>
	</div>
	<!--<div style="padding:5px 0;text-align:right;padding-right:30px">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="save1(this)">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel1(this)">Cancel</a>
	</div>-->
</form>


<script type="text/javascript">
	function save1(target){
		var tr = $(target).closest('.datagrid-row-detail').closest('tr').prev();
		var index = parseInt(tr.attr('datagrid-row-index'));
		saveItem(index);
	}
	function cancel1(target){
		var tr = $(target).closest('.datagrid-row-detail').closest('tr').prev();
		var index = parseInt(tr.attr('datagrid-row-index'));
		console.log(index)
		cancelItem(index);
	}

	function save_cit(target){
		var tr = $(target).closest('.datagrid-row-detail').closest('tr').prev();
		var index = parseInt(tr.attr('datagrid-row-index'));
		saveItemCit(index);
	}
	function cancel_cit(target){
		var tr = $(target).closest('.datagrid-row-detail').closest('tr').prev();
		var index = parseInt(tr.attr('datagrid-row-index'));
		console.log(index)
		cancelItemCit(index);
	}
</script>
