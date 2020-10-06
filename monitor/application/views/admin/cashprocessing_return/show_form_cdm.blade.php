<?php 
	echo "<pre>";
	// print_r($row);
	// print_r($index);
	// print_r($state);
	// print_r($act);
	// print_r($id);
	echo "</pre>";
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
	
	.textbox-prompt {
		color: #c6c6c6 !important;
	}
</style>
<form method="post">
	<div>
		<h1>CDM</h1><br><br>
		
		<?php 
			$r = get_object_vars($row);
			
			
			$data = json_decode($r['data_solve']);
			
			// $ctr  = intval($data->cart_1_seal=="" ? 0 : 1) + 
					// intval($data->cart_2_seal=="" ? 0 : 1) + 
					// intval($data->cart_3_seal=="" ? 0 : 1) + 
					// intval($data->cart_4_seal=="" ? 0 : 1);
			
			$ctr  = 4;
					
			// print_r($data);
		?>
		<input name="act" type="hidden" value="<?=$act?>" readonly>
		<input name="state" type="hidden" value="<?=$state?>" readonly>
		<input name="id" type="hidden" value="<?=$id?>" readonly>
		<input name="id_cashtransit" type="hidden" value="<?=$id_ct?>" readonly>
		<input name="runsheet" type="hidden" readonly>
		<table class="dv-table1_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
			
		</table>
		
		<table class="dv-table3_<?=$index?>" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
			<tr class="data_seal_1">
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="seal_1_100" style="width: 66px; height: 28px;" value="" data-options="prompt:'100K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_1_50" style="width: 66px; height: 28px;" value="" data-options="prompt:'50K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_1_20" style="width: 66px; height: 28px;" value="" data-options="prompt:'20K'" class="easyui-validatebox tb" type="text">
				</td>
			</tr>
			<tr class="data_seal_2">
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="seal_2_100" style="width: 66px; height: 28px;" value="" data-options="prompt:'100K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_2_50" style="width: 66px; height: 28px;" value="" data-options="prompt:'50K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_2_20" style="width: 66px; height: 28px;" value="" data-options="prompt:'20K'" class="easyui-validatebox tb" type="text">
				</td>
			</tr>
			<tr class="data_seal_3">
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="seal_3_100" style="width: 66px; height: 28px;" value="" data-options="prompt:'100K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_3_50" style="width: 66px; height: 28px;" value="" data-options="prompt:'50K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_3_20" style="width: 66px; height: 28px;" value="" data-options="prompt:'20K'" class="easyui-validatebox tb" type="text">
				</td>
			</tr>
			<tr class="data_seal_4">
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="seal_4_100" style="width: 66px; height: 28px;" value="" data-options="prompt:'100K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_4_50" style="width: 66px; height: 28px;" value="" data-options="prompt:'50K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_4_20" style="width: 66px; height: 28px;" value="" data-options="prompt:'20K'" class="easyui-validatebox tb" type="text">
				</td>
			</tr>
			<tr class="data_seal_5">
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="seal_5_100" style="width: 66px; height: 28px;" value="" data-options="prompt:'100K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_5_50" style="width: 66px; height: 28px;" value="" data-options="prompt:'50K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="seal_5_20" style="width: 66px; height: 28px;" value="" data-options="prompt:'20K'" class="easyui-validatebox tb" type="text">
				</td>
			</tr>
			<tr class="data_div">
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="div_100" style="width: 66px; height: 28px;" value="" data-options="prompt:'100K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="div_50" style="width: 66px; height: 28px;" value="" data-options="prompt:'50K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="div_20" style="width: 66px; height: 28px;" value="" data-options="prompt:'20K'" class="easyui-validatebox tb" type="text">
				</td>
			</tr>
			<tr class="data_tbag">
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="tbag_100" style="width: 66px; height: 28px;" value="" data-options="prompt:'100K'" placeholder="Denom 100k" class="easyui-validatebox tb" type="text">
					<input name="tbag_50" style="width: 66px; height: 28px;" value="" data-options="prompt:'50K'" placeholder="Denom 50k" class="easyui-validatebox tb" type="text">
					<input name="tbag_20" style="width: 66px; height: 28px;" value="" data-options="prompt:'20K'" placeholder="Denom 20k" class="easyui-validatebox tb" type="text">
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
			<tr>
				<td>BIG SEAL</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="" id="bag_seal_cdm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
			</tr>
			<tr>
				<td>BAG NO</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="" id="bag_no_cdm_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
			</tr>	
			<tr>
				<td>CASHIER</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<select name="cashier" class="easyui-validatebox cashier" required="true">
						<option value="">- select cashier -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>NO. MEJA</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="nomeja" id="nomeja<?=$index?>" style="height: 28px" class="easyui-validatebox tb" required="true"></input></td>
			</tr>
			<tr>
				<td>JAM PROSES</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="jamproses" id="jamproses<?=$index?>" style="height: 28px" class="easyui-validatebox tb" required="true"></input></td>
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
			var element;
			// jq341(document).scannerDetection({
				// timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
				// avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
				// preventDefault: true,
				// endChar: [13],
				// onComplete: function(barcode, qty) {
					// if(barcode.indexOf(".") != -1){
						// // $("#bag_no_atm_<?=$index?>").textbox('setValue', barcode);
						// $.post('<?php echo base_url().'cashprocessing_return/check_seal'?>', { id: '<?=$id?>', value: barcode })
							// .done(function( data ) {
								// if(data=="true") {
									// $("#bag_no_cdm_<?=$index?>").textbox('setValue', barcode);
								// } else {
									// jq341.notify("Seal "+barcode+", invalid!", "error");
								// }
							// }
						// );
					// }
					// if(barcode.indexOf("A") != -1){
						// // $("#bag_seal_atm_<?=$index?>").textbox('setValue', barcode);
						// $.post('<?php echo base_url().'cashprocessing_return/check_seal'?>', { id: '<?=$id?>', value: barcode })
							// .done(function( data ) {
								// if(data=="true") {
									// $("#bag_seal_cdm_<?=$index?>").textbox('setValue', barcode);
								// } else {
									// jq341.notify("Seal "+barcode+", invalid!", "error");
								// }
							// }
						// );
					// }
				// },
				// onError: function(string, qty) {
					// $("#"+element+"<?=$index?>").textbox('setValue', string);
				// }
			// });
		
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
			
			jq341('.cashier').select2({
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'cashprocessing_return/get_data_kasir'?>',
					delay: 250,
					type: "POST",
					data: function(params) {
						return {
							search: params.term,
							id_cashtransit: '<?=$id?>'
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
			
			jq341(document).ready(function() {
				$('table.dv-table1_<?=$index?> tr').remove();
				// $('table.dv-table2_<?=$index?> tr').remove();
				var cart = parseInt("<?=$ctr?>");
				// alert(cart);
				var row1 = '';
				var row2 = '';
				
				$(".data_seal_1").hide();
				$(".data_seal_2").hide();
				$(".data_seal_3").hide();
				$(".data_seal_4").hide();
				$(".data_seal_5").hide();
				// $(".data_div").hide();
				
				
				for(var i=1; i<=cart; i++) {
					if(i==1) {
						$(".data_seal_1").show();
						cart_1_no = "<?=$r['cart_1_no']?>";
						cart_1_seal = "<?=$data->cart_1_seal?>";
						
						row1 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' SEAL</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_1_seal+'" class="easyui-validatebox tb" type="text"></td>'+
							  '</tr>';
							  
						row2 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' VALUE</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" id="cart_'+i+'_val<?=$index?>" type="text" required="true"></td>'+
							  '</tr>';
					}
					if(i==2) {
						$(".data_seal_2").show();
						cart_2_no = "<?=$r['cart_2_no']?>";
						cart_2_seal = "<?=$data->cart_2_seal?>";
						
						row1 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' SEAL</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_2_seal+'" class="easyui-validatebox tb" type="text"></td>'+
							  '</tr>';
							  
						row2 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' VALUE</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" id="cart_'+i+'_val<?=$index?>" type="text" required="true"></td>'+
							  '</tr>';
					}
					if(i==3) {
						$(".data_seal_3").show();
						cart_3_no = "<?=$r['cart_3_no']?>";
						cart_3_seal = "<?=$data->cart_3_seal?>";
						
						row1 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' SEAL</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_3_seal+'" class="easyui-validatebox tb" type="text"></td>'+
							  '</tr>';
							  
						row2 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' VALUE</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" id="cart_'+i+'_val<?=$index?>" type="text" required="true"></td>'+
							  '</tr>';
					}
					if(i==4) {
						$(".data_seal_4").show();
						cart_4_no = "<?=$r['cart_4_no']?>";
						cart_4_seal = "<?=$data->cart_4_seal?>";
						
						row1 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' SEAL</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_4_seal+'" class="easyui-validatebox tb" type="text"></td>'+
							  '</tr>';
							  
						row2 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' VALUE</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" id="cart_'+i+'_val<?=$index?>" type="text" required="true"></td>'+
							  '</tr>';
					}
					if(i==5) {
						$(".data_seal_5").show();
						cart_5_seal = "<?=$data->cart_5_seal?>";
						
						row1 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' SEAL</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_5_seal+'" class="easyui-validatebox tb" type="text"></td>'+
							  '</tr>';
							  
						row2 +=   '<tr>'+
							  '<td style="border: 0px">CART '+i+' VALUE</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" id="cart_'+i+'_val<?=$index?>" type="text" required="true"></td>'+
							  '</tr>';
					}
					
					
				}
				
				div_value = "<?=$data->div_seal?>";
				row1 +=   '<tr>'+
						  '<td style="border: 0px">DIVERT</td>'+
						  '<td style="border: 0px">:</td>'+
						  '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="divert" style="height: 28px;" value="'+div_value+'" class="easyui-validatebox tb" type="text"></td>'+
						  '</tr>';
			
				row2 +=   '<tr>'+
						  '<td style="border: 0px">DIVERT VALUE</td>'+
						  '<td style="border: 0px">:</td>'+
						  '<td style="border: 0px"><input name="div_val" style="height: 28px" value=""  class="easyui-validatebox tb" type="text" id="div_val<?=$index?>" required="true"></td>'+
						  '</tr>';
						  
				t_bag_value = "<?=$data->t_bag?>";
				if(t_bag_value!=="") {
					$(".data_tbag").hide();
					// row1 +=   '<tr>'+
							  // '<td style="border: 0px">T-BAG</td>'+
							  // '<td style="border: 0px">:</td>'+
							  // '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="divert" style="height: 28px;" value="'+t_bag_value+'" class="easyui-validatebox tb" type="text"></td>'+
							  // '</tr>';
				
					// row2 +=   '<tr>'+
							  // '<td style="border: 0px">T-BAG VALUE</td>'+
							  // '<td style="border: 0px">:</td>'+
							  // '<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="t_bag" style="height: 28px" value=""  class="easyui-validatebox tb" id="t_bag<?=$index?>" type="text" required="true"></td>'+
							  // '</tr>';
				} else {
					$(".data_tbag").hide();
				}
				
				$(".cart_total").textbox('setValue', cart);
				
				$('table.dv-table1_<?=$index?>').append(row1);
				// $('table.dv-table2_<?=$index?>').append(row2);
				
				$('.tb').textbox();
			});
			
			function functionPress(val) {
				val.value = val.value;
			}
		</script>
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
</script>