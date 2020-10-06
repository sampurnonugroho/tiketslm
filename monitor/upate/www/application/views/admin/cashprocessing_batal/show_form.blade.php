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
	
		<input name="act" type="hidden" value="<?=$act?>" readonly>
		<input name="state" type="hidden" value="<?=$state?>" readonly>
		<input name="id" type="hidden" value="<?=$id?>" readonly>
		<input name="id_cashtransit" type="hidden" value="<?=$id_ct?>" readonly>
		<input name="runsheet" type="hidden" readonly>
		
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
			<tr>
				<td colspan="3" style="text-align: center">UANG KERTAS</td>
			</tr>
			<tr>
				<td>100.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_100ks" onfocus="" id="pcs_100000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>50.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_50ks" id="pcs_50000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>20.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_20ks" id="pcs_20000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>10.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_10ks" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>5.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_5ks" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>2.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_2ks" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>1.000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_1ks" id="pcs_10000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
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
					<input name="logam_1ks" id="pcs_5000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>500</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_500s" id="pcs_2000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>	
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>200</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_200s" id="pcs_1000_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>100</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_100s" id="pcs_coin_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
				<td hidden>
					<label class="container">
						<input type="checkbox" name="100k" id="pcs_100000" value="<?=$row->s100k?>" <?=($row->s100k!==null&&$row->s100k!=="0")?"checked":""?> onchange="fungsi_count(this)">
						<span class="checkmark"></span>
					</label>
				</td>	
			</tr>
			<tr>
				<td>TOTAL</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px; text-align: right; font-weight: bold; color: blue">
					<div id="grand_total"></div>
				</td>
			</tr>
			<tr hidden>
				<td>NOMINAL</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="total" id="nama_lokasi" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>
		</table>
		
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:auto">
			<tr>
				<td colspan="3" style="text-align: center"> &nbsp </td>
			</tr>
			<!--<tr>
				<td>REAL NOMINAL</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="nominal" id="real_nominal_<?=$index?>" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input>
				</td>
			</tr>-->
			<tr>
				<td>BIG SEAL</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_seals" id="nama_lokasi" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input></td>
			</tr>
			<tr>
				<td>BAG NO</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="bag_nos" id="nama_lokasi" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','"></input></td>
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
				<td>MEJA</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="nomeja" id="" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','" required="true"></input></td>
			</tr>
			<tr>
				<td>JAM PROSES</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px"><input name="jamproses" id="" style="height: 28px" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','" required="true"></input></td>
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
	
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		
	</div>
	<!--<div style="padding:5px 0;text-align:right;padding-right:30px">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="save1(this)">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel1(this)">Cancel</a>
	</div>-->
</form>


<script type="text/javascript">
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
	
	$(function(){
		var total = 0;
		
        $('#pcs_100000_<?=$index?>').numberbox({
            onChange: function(){
				var pcs_100000_ =  parseInt($('#pcs_100000_<?=$index?>').numberbox('getValue')) * 100000 || 0;
				var pcs_50000_ =  parseInt($('#pcs_50000_<?=$index?>').numberbox('getValue')) * 50000 || 0;
				
				total = pcs_100000_+pcs_50000_;
				$("#grand_total").html(formatNumber(total)	);
            }
        });
		
        $('#pcs_50000_<?=$index?>').numberbox({
            onChange: function(){
                var pcs_100000_ =  parseInt($('#pcs_100000_<?=$index?>').numberbox('getValue')) * 100000 || 0;
				var pcs_50000_ =  parseInt($('#pcs_50000_<?=$index?>').numberbox('getValue')) * 50000 || 0;
				
				total = pcs_100000_+pcs_50000_;
				$("#grand_total").html(formatNumber(total));
            }
        });
    });
	
	function formatNumber(num) {
		return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
	}

	function save1(target){
		var tr = $(target).closest('.datagrid-row-detail').closest('tr').prev();
		var index = parseInt(tr.attr('datagrid-row-index'));
		saveItemCit(index);
	}
	function cancel1(target){
		var tr = $(target).closest('.datagrid-row-detail').closest('tr').prev();
		var index = parseInt(tr.attr('datagrid-row-index'));
		console.log(index)
		cancelItemCit(index);
	}
</script>
