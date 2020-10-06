
<style>
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

	.datagrid-header td, .datagrid-body td, .datagrid-footer td {
		border-width: 0 0 0 0;
		border-style: dotted;
		margin: 0;
		padding: 0;
	}
</style>
<form method="post">
	<div>
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
			<tr>
				<td>WSID</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="id" type="hidden" value="<?=$id?>" readonly>
					<input name="id_detail" type="hidden" readonly>
					<input name="wsid" type="text" class="easyui-validatebox" required="required">
				</td>
			</tr>
			<tr>
				<td>LOKASI</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="lokasi" type="text" class="easyui-validatebox" required="required">
				</td>
			</tr>
			<tr>
				<td>TYPE</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<!--<input name="type" type="text" class="easyui-validatebox">-->
					<select name="type" class="easyui-validatebox" style="width: 150px" required="required">
						<option value="">- select tipe -</option>
						<option value="atm" <?=($type=='atm'?"selected":"")?>> ATM </option>
						<option value="crm" <?=($type=='crm'?"selected":"")?>> CRM </option>
						<option value="cdm" <?=($type=='cdm'?"selected":"")?>> CDM </option>
						<option value="mfd" <?=($type=='mfd'?"selected":"")?>> MFD </option>
					</select>
				</td>
			</tr>
			<tr>
				<td>PAKET</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="paket" type="text" class="easyui-validatebox" required="required">
				</td>
			</tr>
			<tr>
				<td>DENOMINASI</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<select name="denom" class="easyui-validatebox" style="width: 150px">
						<option value="">- select denom -</option>
						<option value="50000" <?=($type=='atm'?"selected":"")?>> 50.000 </option>
						<option value="100000" <?=($type=='crm'?"selected":"")?>> 100.000 </option>
					</select>
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
			<tr>
				<td>LIMIT MINIMUM</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="tgl_min_dari" type="text" class="easyui-validatebox" placeholder="Dari Tanggal" required="required">
					<input name="tgl_min_hingga" type="text" class="easyui-validatebox" placeholder="Hingga Tanggal" required="required">
					<input name="limit_min" type="text" class="easyui-validatebox" placeholder="Nominal Limit" required="required">
				</td>
			</tr>
			<tr>
				<td>LIMIT MAXIMUM</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="tgl_max_dari" type="text" class="easyui-validatebox" placeholder="Dari Tanggal" required="required">
					<input name="tgl_max_hingga" type="text" class="easyui-validatebox" placeholder="Hingga Tanggal" required="required">
					<input name="limit_max" type="text" class="easyui-validatebox" placeholder="Nominal Limit" required="required">
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
			<tr>
				<td>CASSETTE</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="ctr" type="text" class="easyui-validatebox" required="required">
				</td>
			</tr>
			<tr>
				<td>DIVERT</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="reject" type="text" class="easyui-validatebox" required="required">
				</td>
			</tr>
			<tr>
				<td>INTERVAL</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="interval_isi" type="text" class="easyui-validatebox" required="required">
				</td>
			</tr>
			<tr>
				<td>SIFAT</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<select name="sifat" class="easyui-validatebox" style="width: 150px" required="required">
						<option value="">- select sifat -</option>
						<option value="insidentil" <?=($sifat=='insidentil'?"selected":"")?>> Insidentil </option>
						<option value="permanen" <?=($sifat=='permanen'?"selected":"")?>> Permanent </option>
					</select>
				</td>
			</tr>
			<tr>
				<td>TANGGAL EFEKTIF</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="tgl_efektif" id="dd" type="text" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="required">
				</td>
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
	<br><br><br><br><br><br>
	<br><br><br><br><br><br>
	<br><br><br><br><br><br>
</form>
<script type="text/javascript">
	function myformatter(date){
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
	}
	function myparser(s){
		if (!s) return new Date();
		var ss = (s.split('-'));
		var y = parseInt(ss[0],10);
		var m = parseInt(ss[1],10);
		var d = parseInt(ss[2],10);
		if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
			return new Date(y,m-1,d);
		} else {
			return new Date();
		}
	}

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
