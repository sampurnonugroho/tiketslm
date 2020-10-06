<?php 
	if($flag=="ADD") {
		// echo "<pre>";
		// print_r($id);
		// echo "<br>";
		// print_r($row);
		// echo "</pre>";
?>

<style>
	fieldset{margin-bottom: 1em;width:300px;}
	#suggest{position:absolute;z-index:5;border-left:silver 1px solid;padding:0 0 0 10px;background-color:#ebebeb}
	span.pilihan{display:block;cursor:pointer;padding:5px}
</style>
<form method="post">
	<div>
		<table class="dv-table" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;width:50%">
			<tr>
				<td>Area Number</td>
				<td>:</td>
				<!--<td>
					<select name="id_bank" class="js-example-basic-singleasd full-width easyui-validatebox" required="true">
						<option value="">- select bank -</option>
					</select>
				</td>-->
				<td>
					<input name="date" type="hidden" value="<?=$date?>" readonly>
					<select name="run_number" class="easyui-validatebox run_sheet_number" required="true">
						<option value="">- select runsheet -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>First Custodian</td>
				<td>:</td>
				<td>
					<select name="custodian_1" class="easyui-validatebox custodian_1">
						<option value="">- select custodi -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Second Custodian</td>
				<td>:</td>
				<td>
					<select name="custodian_2" class="easyui-validatebox custodian_2">
						<option value="">- select custodi -</option>
					</select>
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;width:50%">
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
<?php 
	}
?>

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
		var page    = '<?=base_url()?>operational/suggest';
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
	function pilih_kota(id, detail) {
		$('#src').val(id);
		
		var data = detail;
		array = data.split("-");
		
		$("#nama_bank").val(array[0]);
		$("#nama_lokasi").val(array[1]);
		$("#nama_sektor").val(array[2]);
		$("#nama_denom").val(array[3]);
	}

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
	console.log( "2nd loaded jQuery version (jq162xx): " + jq341.fn.jquery + "<br>" );

	jq341(document).ready(function()
	{
		jq341('.run_sheet_number').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'operational/suggest_data_client'?>',
				delay: 250,
				type: "POST",
				data: function(params) {
					return {
						search: params.term,
						id_cashtransit: '<?=$id?>',
						date: '<?=$date?>'
					}
				},
				processResults: function (data, page) {
					return {
						results: data
					};
				}
			}
		}).on('select2:select', function (evt) {
			var data = jq341(".js-example-basic-singleasd option:selected").text();
			array = data.split("-");
			
			$("#nama_bank").val(array[0]);
			$("#nama_lokasi").val(array[1]);
			$("#nama_sektor").val(array[2]);
		});
		
		jq341('.custodian_1').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'operational/suggest_custodi'?>',
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
		
		jq341('.custodian_2').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'operational/suggest_custodi2'?>',
				delay: 250,
				type: "POST",
				data: function(params) {
					return {
						search: params.term,
						id_cashtransit: '<?=$id?>',
						prev_id: jq341(".custodian_1 option:selected").val()
					}
				},
				processResults: function (data, page) {
					return {
						results: data
					};
				}
			}
		}).on('select2:select', function (evt) {
			
		});
	});
</script>