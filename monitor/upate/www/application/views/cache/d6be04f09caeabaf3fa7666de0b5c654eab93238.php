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
					jq341.notify("CRM "+string, "success");
					if(string=='q') {
						$.post('<?php echo base_url().'logistic_in_use/get_seal_demo'?>', { value: string, barcode: barcode })
							.done(function( data ) {
								barcode = data;
								if($("#cart_1_seal_crm_<?=$index?>").val()=="") {
									if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
										$("#divert_crm_<?=$index?>").val()!==barcode) {
										
										$("#cart_1_seal_crm_<?=$index?>").textbox('setValue', barcode);
									}
								} else if($("#cart_2_seal_crm_<?=$index?>").val()=="") {
									if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
										$("#divert_crm_<?=$index?>").val()!==barcode) {
										
										$("#cart_2_seal_crm_<?=$index?>").textbox('setValue', barcode);
									}
								} else if($("#cart_3_seal_crm_<?=$index?>").val()=="") {
									if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
										$("#divert_crm_<?=$index?>").val()!==barcode) {
										
										$("#cart_3_seal_crm_<?=$index?>").textbox('setValue', barcode);
									}
								} else if($("#cart_4_seal_crm_<?=$index?>").val()=="") {
									if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
										$("#divert_crm_<?=$index?>").val()!==barcode) {
										
										$("#cart_4_seal_crm_<?=$index?>").textbox('setValue', barcode);
									}
								} else if($("#divert_crm_<?=$index?>").val()=="") {
									if($("#cart_1_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_2_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_3_seal_crm_<?=$index?>").val()!==barcode && 
										$("#cart_4_seal_crm_<?=$index?>").val()!==barcode && 
										$("#divert_crm_<?=$index?>").val()!==barcode) {
										
										$("#divert_crm_<?=$index?>").textbox('setValue', barcode);
									}
								}
							}
						);
					}
					
					if(string=='w') {
						$.post('<?php echo base_url().'logistic_in_use/get_seal_demo'?>', { value: string, barcode: barcode })
							.done(function( data ) {
								$("#bag_seal_crm_<?=$index?>").textbox('setValue', data);
							}
						);
					}
					
					if(string=='e') {
						$.post('<?php echo base_url().'logistic_in_use/get_seal_demo'?>', { value: string, barcode: barcode })
							.done(function( data ) {
								$("#bag_no_crm_<?=$index?>").textbox('setValue', data);
							}
						);
					}
					
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
	</div>
</form>