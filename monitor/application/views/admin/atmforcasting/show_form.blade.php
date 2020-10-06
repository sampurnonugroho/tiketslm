
<style>
	fieldset{margin-bottom: 1em;width:300px;}
	#suggest{position:absolute;z-index:5;border-left:silver 1px solid;padding:0 0 0 10px;background-color:#ebebeb}
	span.pilihan{display:block;cursor:pointer;padding:5px}
</style>
<form method="post">
	<div>
		<table class="dv-table" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;">
			<tr>
				<td>ID BANK</td>
				<td>:</td>
				<td>
					<input name="id_atmforcasting" type="" value="<?=$id?>" readonly>
					<input name="id_bank" id="src" onkeypress="suggest(this.value);" class="easyui-validatebox"></input><div id="suggest"></div>
				</td>
			</tr>
			<tr>
				<td>BANK</td>
				<td>:</td>
				<td><input name="bank" id="nama_bank" class="easyui-validatebox" readonly="true"></input></td>
			</tr>
			<tr>
				<td>LOCATION</td>
				<td>:</td>
				<td><input name="lokasi" id="nama_lokasi" class="easyui-validatebox" readonly="true"></input></td>
			</tr>
			<tr>
				<td>ZONE</td>
				<td>:</td>
				<td><input name="sektor" id="nama_sektor" class="easyui-validatebox" readonly="true"></input></td>
			</tr>
			<tr>
				<td>JENIS</td>
				<td>:</td>
				<td>
					<select name="jenis" class="easyui-validatebox" style="width:100%" required="true">
						<option value="">- select type -</option>
						<option value="ATM">ATM</option>
						<option value="CDM">CDM</option>
						<option value="CRM">CRM</option>
					</select>
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;">
			<tr>
				<td>MERK</td>
				<td>:</td>
				<td><input name="merk" class="easyui-validatebox" required="true"></input></td>
			</tr>
			<tr>
				<td>DENOM</td>
				<td>:</td>
				<td><input name="denom" class="easyui-validatebox" required="true"></input></td>
			</tr>
			<tr>
				<td>CARTRIDGE</td>
				<td>:</td>
				<td><input name="cartridge" class="easyui-validatebox" required="true"></input></td>
			</tr>
			<tr>
				<td>NO CARTRIDGE</td>
				<td>:</td>
				<td><input name="no_cartridge" class="easyui-validatebox" required="true"></input></td>
			</tr>
			<tr>
				<td>STATUS</td>
				<td>:</td>
				<td>
					<select name="status" class="easyui-validatebox" style="width:100%" required="true">
						<option value="">- select type -</option>
						<option value="EMPTY">EMPTY</option>
						<option value="LOW">LOW</option>
						<option value="NORMAL">NORMAL</option>
					</select>
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;">
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
	
	
	//Fungsi untuk autosuggest
	function suggest(src)
	{
		console.log(src);
		var page    = '<?=base_url()?>cashtransit/suggest';
		console.log(page);
		if(src.length>=2){
			var loading = '<p align="center">Loading ...</p>';
			showStuff('suggest');
			$('#suggest').html(loading);
			$.ajax({
				url: page,
				data : 'src='+src,
				type: "post", 
				dataType: "html",
				timeout: 10000,
				success: function(response){
					$('#suggest').html(response);
				}
			});
		} else {
			$('#suggest').html('');
		}
	}

	//Fungsi untuk memilih kota dan memasukkannya pada input text
	function pilih_kota(id, detail) {
		$('#src').val(id);
		
		var data = detail;
		array = data.split("-");
		
		$("#nama_bank").val(array[0]);
		$("#nama_lokasi").val(array[1]);
		$("#nama_sektor").val(array[2]);
	}

	//menampilkan form div
	function showStuff(id) {
		document.getElementById(id).style.display = 'block';
	}
	//menyembunyikan form
	function hideStuff(id) {
		document.getElementById(id).style.display = 'none';
	}
	
	
	
	// jq341 = jQuery.noConflict();
	// console.log( "<h3>After $.noConflict(true)</h3>" );
	// console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );

	// jq341(document).ready(function()
	// {
		// jq341(".js-example-basic-singleSSS").select2({no_results_text: "Oops, nothing found!"}); 
		// jq341('.js-example-basic-singleasd').select2({
			// tokenSeparators: [','],
			// ajax: {
				// dataType: 'json',
				// url: '<?php echo base_url().'select/select_client'?>',
				// delay: 250,
				// type: "POST",
				// data: function(params) {
					// return {
						// search: params.term
					// }
				// },
				// processResults: function (data, page) {
					// return {
						// results: data
					// };
				// }
			// }
		// }).on('select2:select', function (evt) {
			// var data = jq341(".js-example-basic-singleasd option:selected").text();
			// array = data.split("-");
			
			// $("#nama_bank").val(array[0]);
			// $("#nama_lokasi").val(array[1]);
			// $("#nama_sektor").val(array[2]);
		// });
	// });
</script>
