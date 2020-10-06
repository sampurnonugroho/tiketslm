<?php 
	// echo "<pre>";
	// print_r($row);
	// print_r($index);
	// print_r($id);
	// echo "</pre>";
?>
<style>
	fieldset{margin-bottom: 1em;width:300px;}
	#suggest{position:absolute;z-index:5;border-left:silver 1px solid;padding:0 0 0 10px;background-color:#ebebeb}
	span.pilihan{display:block;cursor:pointer;padding:5px}
</style>
<form method="post">
	<div>
		<table class="dv-table1" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;">
			<tr>
				<td>ID</td>
				<td>:</td>
				<!--<td>
					<input name="id_cashtransit" type="hidden" value="<?=$id?>" readonly>
					<input name="id_bank" id="src" onkeypress="suggest(this.value);" class="easyui-validatebox easyui-textbox"></input><div id="suggest"></div>
				</td>-->
				<td>
					<input name="id_cashtransit" type="hidden" value="<?=$id?>" readonly>
					<?=($flag=="ADD" ? '' : '<input name="id_detail" type="hidden" value="'.$row->id.'" readonly><input name="id_bank2" type="hidden" value="'.$row->id_bank.'" readonly>')?>
					<select name="id_bank" class="easyui-validatebox run_sheet_number<?=$index?>" style="width: 100%" <?=($flag=="ADD" ? 'required="true"' : 'disabled="disabled"')?>>
						<option value="">- select client -</option>
						<?=($flag=="ADD" ? "" : "<option value='$row->id_bank'>$row->wsid - $row->lokasi</option>")?>
					</select>
				</td>
			</tr>
			<tr>
				<td>BRANCH</td>
				<td>:</td>
				<td><input name="bank" id="nama_branch" class="easyui-validatebox easyui-textbox nama_branch" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>GA</td>
				<td>:</td>
				<td><input name="sektor" id="nama_ga" class="easyui-validatebox easyui-textbox nama_ga" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>BANK</td>
				<td>:</td>
				<td><input name="bank" id="nama_bank" class="easyui-validatebox easyui-textbox nama_bank" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>ACT</td>
				<td>:</td>
				<td><input name="act" id="nama_act" class="easyui-validatebox easyui-textbox nama_act" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>BRAND</td>
				<td>:</td>
				<td><input name="brand" id="nama_brand" class="easyui-validatebox easyui-textbox nama_brand" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>MODEL</td>
				<td>:</td>
				<td><input name="model" id="nama_model" class="easyui-validatebox easyui-textbox nama_model" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>LOCATION</td>
				<td>:</td>
				<td><input name="lokasi" id="nama_location" class="easyui-validatebox easyui-textbox nama_location" disabled="disabled"></input></td>
			</tr>
			
		</table>
		<!--<table class="dv-table2" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;">
			<tr>
				<td>BAG SEAL</td>
				<td>:</td>
				<td><input name="lokasi" id="nama_lokasi" class="easyui-validatebox easyui-textbox"></input></td>
			</tr>
			<tr>
				<td>BAG NO</td>
				<td>:</td>
				<td><input name="lokasi" id="nama_lokasi" class="easyui-validatebox easyui-textbox"></input></td>
			</tr>	
		</table>-->
		<table class="dv-table3<?=$index?>" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;">
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
				data : 'src='+src+'&id=<?=$id?>',
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
	// function pilih_kota(id, detail) {
		// $('#src').val(id);
		
		// var data = detail;
		// array = data.split("-");
		
		// $("#nama_bank").val(array[0]);
		// $("#nama_lokasi").val(array[1]);
		// $("#nama_sektor").val(array[2]);
	// }

	//menampilkan form div
	function showStuff(id) {
		document.getElementById(id).style.display = 'block';
	}
	//menyembunyikan form
	function hideStuff(id) {
		document.getElementById(id).style.display = 'none';
	}
	
	jq341 = jQuery.noConflict();
	console.log( "<h3>After $.noConflict(true)</h3>" );
	console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
	var id_ct = "<?=$id?>";
	var id_bank = "<?=$id_bank?>";
	if(id_bank!==null) {
		// alert(id_ct+" "+id_bank)
		$.ajax({url: '<?php echo base_url().'cashreplenish/get_data_client2'?>', data: {id_ct: id_ct, id_bank: id_bank}, success: function(result){
			console.log(result);
			result = JSON.parse(result);
			
			$(".nama_branch").textbox('setValue', result.branch);
			$(".nama_ga").textbox('setValue', result.ga);
			$(".nama_bank").textbox('setValue', result.bank);
			$(".nama_act").textbox('setValue', result.act);
			$(".nama_brand").textbox('setValue', result.brand);
			$(".nama_model").textbox('setValue', result.model);
			$(".nama_location").textbox('setValue', result.location);
			
			// $('table.dv-table3 tr').remove();
			$(".dv-table3<?=$index?>").find("tr:not(:last)").remove();
			if(result.denom=="100000") {
				denom = "100K";
				val_denom = result.pcs_100000;
				if(result.ctr==null) {
					ctr = "";
				} else {
					ctr = result.ctr;
				}
			} else {
				denom = "50K";
				val_denom = result.pcs_50000;
				if(result.ctr==null) {
					ctr = "";
				} else {
					ctr = result.ctr;
				}
			}
			
			var row1 = '';
			var row2 = '';
			if(result.act=="ATM") {
				var row3 = '<tr>'+
							'<td>TOTAL CART</td>'+
							'<td>:</td>'+
							'<td><input name="ctr" class="easyui-validatebox tb" value="'+ctr+'"></input></td>'+
							'</tr>';
			} else if(result.act=="CRM") {
				var row3 = '<tr>'+
							'<td>TOTAL CART</td>'+
							'<td>:</td>'+
							'<td><input name="ctr" class="easyui-validatebox tb" value="5"></input></td>'+
							'</tr>';
			} else if(result.act=="CDM") {
				var row3 = '<tr>'+
							'<td>TOTAL CART</td>'+
							'<td>:</td>'+
							'<td><input name="ctr" class="easyui-validatebox tb" value="4"></input></td>'+
							'</tr>';
			}
			
			if(result.act=="ATM") {
				row3 +=  '<tr>'+
							'<td>DENOM '+denom+'</td>'+
							'<td>:</td>'+
							'<td><input name="pcs_'+result.denom+'" class="easyui-validatebox tb" value="'+val_denom+'"></input></td>'+
						'</tr>';
			}
			$('table.dv-table1').append(row1);
			$('table.dv-table2').append(row2);
			$(row3).insertBefore(".dv-table3<?=$index?> tr:first");
			
			$('.tb').textbox();
		}});
	}
	
	jq341(document).ready(function()
	{
		jq341(".run_sheet_number<?=$index?>").select2({
			minimumResultsForSearch: 1,
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'cashreplenish/suggest_data_client'?>',
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
			var id = jq341(".run_sheet_number<?=$index?> option:selected").val();
			// alert(id);
			
			$.ajax({url: '<?php echo base_url().'cashreplenish/get_data_client'?>', data: {id: id}, success: function(result){
				result = JSON.parse(result);
				console.log(result);
				
				// alert(result.act);
				$(".nama_branch").textbox('setValue', result.branch);
				$(".nama_ga").textbox('setValue', result.ga);
				$(".nama_bank").textbox('setValue', result.bank);
				$(".nama_act").textbox('setValue', result.act);
				$(".nama_brand").textbox('setValue', result.brand);
				$(".nama_model").textbox('setValue', result.model);
				$(".nama_location").textbox('setValue', result.location);
				
				// $('table.dv-table3 tr').remove();
				$(".dv-table3<?=$index?>").find("tr:not(:last)").remove();
				if(result.denom=="100000") {
					denom = "100K";
				} else {
					denom = "50K";
				}
				
				val_denom = result.val_denom;
				if(result.ctr==null) {
					ctr = "";
				} else {
					ctr = result.ctr;
				}
				
				var row1 = '';
				var row2 = '';
				if(result.act=="ATM") {
					var row3 = '<tr>'+
								'<td>TOTAL CART</td>'+
								'<td>:</td>'+
								'<td><input name="ctr" class="easyui-validatebox tb" value="'+ctr+'"></input></td>'+
								'</tr>';
				} else if(result.act=="CRM") {
					var row3 = '<tr>'+
								'<td>TOTAL CART</td>'+
								'<td>:</td>'+
								'<td><input name="ctr" class="easyui-validatebox tb" value="5"></input></td>'+
								'</tr>';
				} else if(result.act=="CDM") {
					var row3 = '<tr>'+
								'<td>TOTAL CART</td>'+
								'<td>:</td>'+
								'<td><input name="ctr" class="easyui-validatebox tb" value="4"></input></td>'+
								'</tr>';
				}
				
				if(result.act=="ATM") {
					row3 +=  '<tr>'+
								'<td>DENOM '+denom+'</td>'+
								'<td>:</td>'+
								'<td><input name="pcs_'+result.denom+'" class="easyui-validatebox tb" value="'+val_denom+'"></input></td>'+
							'</tr>';
				}
				$('table.dv-table1').append(row1);
				$('table.dv-table2').append(row2);
				$(row3).insertBefore(".dv-table3<?=$index?> tr:first");
				
				$('.tb').textbox();
			}});
		});
	});
</script>
