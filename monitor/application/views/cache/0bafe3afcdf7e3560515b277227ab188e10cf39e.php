<?php 
	$scanner_active = false;
	
	// echo "<pre>";
	// print_r($row);
	
	$metode = $row->metode;
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
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:250px">
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
					<input name="kertas_100k" id="kertas_100k<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
			<tr>
				<td>50.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_50k" id="kertas_50k<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
			<tr>
				<td>20.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_20k" id="kertas_20k<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
			<tr>
				<td>10.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_10k" id="kertas_10k<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
			<tr>
				<td>5.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_5k" id="kertas_5k<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
			<tr>
				<td>2.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_2k" id="kertas_2k<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
			<tr>
				<td>1.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_1k" id="kertas_1k<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:250px">
			<tr>
				<td colspan="3" style="text-align: center">UANG LOGAM</td>
			</tr>
			<tr>
				<td>1.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_1k" id="logam_1k<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
			<tr>
				<td>500</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_500" id="logam_500<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>	
			</tr>
			<tr>
				<td>200</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_200" id="logam_200<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
			<tr>
				<td>100</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_100" id="logam_100<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:250px">
			<tr>
				<td colspan="3" style="text-align: center">PAPER SEAL</td>
			</tr>
			<tr>
				<td>DARI NOMOR</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px; width: 150px">
					<select name="cart_1_seal" class="easyui-validatebox cart_1_seal" style="width: 100%">
						<option value="">- select dari -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>HINGGA NOMOR</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<select name="cart_2_seal" class="easyui-validatebox cart_1_seal" style="width: 100%">
						<option value="">- select dari -</option>
					</select>
				</td>
			</tr>
			<tr class="hide_bag">
				<td>BAG SEAL</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="bag_seal" id="bag_seal_cit_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox hide_bag"></input>
				</td>
			</tr>
			<tr class="hide_bag">
				<td>BAG NO</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="bag_no" id="bag_no_cit_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox hide_bag"></input>
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
				
				jq341(".security_1").select2({
					
				});
			});
			
			
			$(':input').live('focus', function(){
				console.log($(this));
				console.log($(this)[0]);
				console.log($(this)[0].nextElementSibling);
				console.log($(this)[0].nextElementSibling.name);
				
				element = $(this)[0].nextElementSibling.name;
				
				alert("#"+element+"<?=$index?>");
				
				if($("#"+element+"<?=$index?>").val()!=="") {
					$("#"+element+"<?=$index?>").textbox('setValue', '');
				}
			});
			
			
			<?php } ?>
			
			// $(':input').live('focus', function(){
				// // console.log($(this));
				// // console.log($(this)[0]);
				// // console.log($(this)[0].nextElementSibling);
				// // console.log($(this)[0].nextElementSibling.name);
				
				// element = $(this)[0].nextElementSibling.name;
				
				// if($("#"+element+"<?=$index?>").val()!=="") {
					// $("#"+element+"<?=$index?>").textbox('setValue', '');
				// }
			// });
			
			jq341('.cart_1_seal').select2({
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'cashprocessing_cit/suggest_seal_1'?>',
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
				$(".hide_bag").hide();
			});
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
