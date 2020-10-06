<?php 
	echo "<pre>";
	// print_r($row);
	// print_r($index);
	// print_r($state);
	// print_r($act);
	// print_r($id);
	echo "</pre>";
?>
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
			
			<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;">
				<tr>
					<td>100K</td>
					<td>:</td>
					<td>
						<input name="state" type="hidden" value="<?=$state?>" readonly>
						<input name="id" type="hidden" value="<?=$id?>" readonly>
						<input name="id_cashtransit" type="hidden" value="<?=$id_ct?>" readonly>
						<input name="runsheet" type="hidden" readonly>
						<input name="pcs_100000" id="pcs_100000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
					<td>
						<label class="container">
							<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
							<span class="checkmark"></span>
						</label>
					</td>	
				</tr>
				<tr>
					<td>50K</td>
					<td>:</td>
					<td>
						<input name="pcs_50000" id="pcs_50000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
					<td>
						<label class="container">
							<input type="checkbox" name="50k" id="pcs_50000" value="<?=$row->s50k?>" <?=($row->s50k!==null&&$row->s50k!=="0")?"checked":""?> onchange="fungsi_count(this)">
							<span class="checkmark"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td>20K</td>
					<td>:</td>
					<td>
						<input name="pcs_20000" id="pcs_20000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
					<td>
						<label class="container">
							<input type="checkbox" name="20k" id="pcs_20000" value="<?=$row->s20k?>" <?=($row->s20k!==null&&$row->s20k!=="0")?"checked":""?> onchange="fungsi_count(this)">
							<span class="checkmark"></span>
						</label>
					</td>	
				</tr>
				<tr>
					<td>10K</td>
					<td>:</td>
					<td>
						<input name="pcs_10000" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
					<td>
						<label class="container">
							<input type="checkbox" name="10k" id="pcs_10000" value="<?=$row->s10k?>" <?=($row->s10k!==null&&$row->s10k!=="0")?"checked":""?> onchange="fungsi_count(this)">
							<span class="checkmark"></span>
						</label>
					</td>	
				</tr>
				<tr>
					<td>5K</td>
					<td>:</td>
					<td>
						<input name="pcs_5000" id="pcs_5000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
					<td>
						<label class="container">
							<input type="checkbox" name="5k" id="pcs_5000" value="<?=$row->s5k?>" <?=($row->s5k!==null&&$row->s5k!=="0")?"checked":""?> onchange="fungsi_count(this)">
							<span class="checkmark"></span>
						</label>
					</td>	
				</tr>
				<tr>
					<td>2K</td>
					<td>:</td>
					<td>
						<input name="pcs_2000" id="pcs_2000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
					<td>
						<label class="container">
							<input type="checkbox" name="2k" id="pcs_2000" value="<?=$row->s2k?>" <?=($row->s2k!==null&&$row->s2k!=="0")?"checked":""?> onchange="fungsi_count(this)">
							<span class="checkmark"></span>
						</label>
					</td>	
				</tr>
				<tr>
					<td>1K</td>
					<td>:</td>
					<td>
						<input name="pcs_1000" id="pcs_1000_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
					<td>
						<label class="container">
							<input type="checkbox" name="1k" id="pcs_1000" value="<?=$row->s1k?>" <?=($row->s1k!==null&&$row->s1k!=="0")?"checked":""?> onchange="fungsi_count(this)">
							<span class="checkmark"></span>
						</label>
					</td>	
				</tr>
				<tr>
					<td>COIN</td>
					<td>:</td>
					<td>
						<input name="pcs_coin" id="pcs_coin_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
					<td>
						<label class="container">
							<input type="checkbox" name="coins" id="pcs_coin" value="<?=$row->coin?>" <?=($row->coin!==null&&$row->coin!=="0")?"checked":""?> onchange="fungsi_count(this)">
							<span class="checkmark"></span>
						</label>
					</td>	
				</tr>
				<tr>
					<td>NOMINAL</td>
					<td>:</td>
					<td>
						<input name="total" id="nama_lokasi" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
				</tr>
			</table>
			<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:400px">
				<tr>
					<td>REAL NOMINAL</td>
					<td>:</td>
					<td>
						<input name="nominal" id="real_nominal_<?=$index?>" style="height: 28px" class="easyui-validatebox easyui-textbox"></input>
					</td>
				</tr>
				<tr>
					<td>BAG SEAL</td>
					<td>:</td>
					<td><input name="bag_seal" id="nama_lokasi" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
				</tr>
				<tr>
					<td>BAG NO</td>
					<td>:</td>
					<td><input name="bag_no" id="nama_lokasi" style="height: 28px" class="easyui-validatebox easyui-textbox" required="true"></input></td>
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
				var count = "<?=($row->nominal!=='0')?$row->nominal:'0'?>";
				if(count=="") {
					count = 0;
				} else {
					count = parseInt(count);
				}
				function fungsi_count(param) {
					console.log(param.checked);
					id = param.id;
					value = $("#"+param.id+"_<?=$index?>").val();
					pengali = id.split("_").pop();
					
					if(param.checked==true) {
						if(pengali!=="coin") {
							hasil = parseInt(value)*parseInt(pengali);
							count = count + hasil;
						} else {
							count = count + parseInt(value);
						}
						param.value = value;
					} else {
						if(pengali!=="coin") {
							hasil = parseInt(value)*parseInt(pengali);
							count = count - hasil;
						} else {
							count = count - parseInt(value);
						}
						param.value = 0;
					}
					console.log(count);
					
					$("#real_nominal_<?=$index?>").textbox('setValue', count);
				}
			</script>
		<?php elseif($state=="ro_atm"): ?>
			
			<?php 
				$r = get_object_vars($row);
				
				
				$data = json_decode($r['data_solve']);
				
				$ctr  = intval($data->cart_1_seal=="" ? 0 : 1) + 
						intval($data->cart_2_seal=="" ? 0 : 1) + 
						intval($data->cart_3_seal=="" ? 0 : 1) + 
						intval($data->cart_4_seal=="" ? 0 : 1);
						
				// echo $ctr;
			?>
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
					<td>CASHIER</td>
					<td>:</td>
					<td><input name="cashier" style="height: 28px" class="easyui-validatebox tb" required="true"></input></td>
				</tr>
				<tr>
					<td>NO. MEJA</td>
					<td>:</td>
					<td><input name="nomeja" style="height: 28px" class="easyui-validatebox tb" required="true"></input></td>
				</tr>
				<tr>
					<td>JAM PROSES</td>
					<td>:</td>
					<td><input name="jamproses" style="height: 28px" class="easyui-validatebox tb" required="true"></input></td>
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
				
			
				
				
				jq341(document).ready(function() {
					$('table.dv-table1_<?=$index?> tr').remove();
					$('table.dv-table2_<?=$index?> tr').remove();
					var cart = parseInt("<?=$ctr?>");
					var row1 = '';
					var row2 = '';
					
					
					for(var i=1; i<=cart; i++) {
						if(i==1) {
							cart_1_no = "<?=$r['cart_1_no']?>";
							cart_1_seal = "<?=$data->cart_1_seal?>";
							
							row1 +=   '<tr>'+
								  '<td style="border: 0px">CART '+i+' SEAL</td>'+
								  '<td style="border: 0px">:</td>'+
								  '<td style="border: 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_1_seal+'" class="easyui-validatebox tb" type="text"></td>'+
								  '</tr>';
								  
							row2 +=   '<tr>'+
								  '<td style="border: 0px">CART '+i+' VALUE</td>'+
								  '<td style="border: 0px">:</td>'+
								  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" type="text" required="true"></td>'+
								  '</tr>';
						}
						if(i==2) {
							cart_2_no = "<?=$r['cart_2_no']?>";
							cart_2_seal = "<?=$data->cart_2_seal?>";
							
							row1 +=   '<tr>'+
								  '<td style="border: 0px">CART '+i+' SEAL</td>'+
								  '<td style="border: 0px">:</td>'+
								  '<td style="border: 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_2_seal+'" class="easyui-validatebox tb" type="text"></td>'+
								  '</tr>';
								  
							row2 +=   '<tr>'+
								  '<td style="border: 0px">CART '+i+' VALUE</td>'+
								  '<td style="border: 0px">:</td>'+
								  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" type="text" required="true"></td>'+
								  '</tr>';
						}
						if(i==3) {
							cart_3_no = "<?=$r['cart_3_no']?>";
							cart_3_seal = "<?=$data->cart_3_seal?>";
							
							row1 +=   '<tr>'+
								  '<td style="border: 0px">CART '+i+' SEAL</td>'+
								  '<td style="border: 0px">:</td>'+
								  '<td style="border: 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_3_seal+'" class="easyui-validatebox tb" type="text"></td>'+
								  '</tr>';
								  
							row2 +=   '<tr>'+
								  '<td style="border: 0px">CART '+i+' VALUE</td>'+
								  '<td style="border: 0px">:</td>'+
								  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" type="text" required="true"></td>'+
								  '</tr>';
						}
						if(i==4) {
							cart_4_no = "<?=$r['cart_4_no']?>";
							cart_4_seal = "<?=$data->cart_4_seal?>";
							
							row1 +=   '<tr>'+
								  '<td style="border: 0px">CART '+i+' SEAL</td>'+
								  '<td style="border: 0px">:</td>'+
								  '<td style="border: 0px"><input name="cart_'+i+'_seal" style="height: 28px;" value="'+cart_4_seal+'" class="easyui-validatebox tb" type="text"></td>'+
								  '</tr>';
								  
							row2 +=   '<tr>'+
								  '<td style="border: 0px">CART '+i+' VALUE</td>'+
								  '<td style="border: 0px">:</td>'+
								  '<td style="border: 0px"><input name="cart_'+i+'_val" style="height: 28px" value=""  class="easyui-validatebox tb" type="text" required="true"></td>'+
								  '</tr>';
						}
						
						
					}
					
					div_value = "<?=$data->div_seal?>";
					row1 +=   '<tr>'+
							  '<td style="border: 0px">DIV</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px"><input name="divert" style="height: 28px;" value="'+div_value+'" class="easyui-validatebox tb" type="text"></td>'+
							  '</tr>';
				
					row2 +=   '<tr>'+
							  '<td style="border: 0px">DIV VALUE</td>'+
							  '<td style="border: 0px">:</td>'+
							  '<td style="border: 0px"><input name="div_val" style="height: 28px" value=""  class="easyui-validatebox tb" type="text" required="true"></td>'+
							  '</tr>';
					
					$(".cart_total").textbox('setValue', cart);
					
					$('table.dv-table1_<?=$index?>').append(row1);
					$('table.dv-table2_<?=$index?>').append(row2);
					
					$('.tb').textbox();
				});
				
				function functionPress(val) {
					val.value = val.value;
				}
			</script>
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
</script>
