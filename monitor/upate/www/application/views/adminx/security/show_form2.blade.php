<style>
	fieldset{margin-bottom: 1em;width:300px;}
	#suggest{position:absolute;z-index:5;border-left:silver 1px solid;padding:0 0 0 10px;background-color:#ebebeb}
	span.pilihan{display:block;cursor:pointer;padding:5px}
</style>
<form method="post">
	<div>
		<table class="dv-table" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;width:400px">
			<tr>
				<td>Area Number</td>
				<td>:</td>
				<!--<td>
					<select name="id_bank" class="js-example-basic-singleasd full-width easyui-validatebox" required="true">
						<option value="">- select bank -</option>
					</select>
				</td>-->
				<td>
					<input name="id_cashtransit" type="hidden" value="<?=$id?>" readonly>
					<select name="run_number" class="easyui-validatebox run_sheet_number" required="true">
						<option value="">- select runsheet -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Police Number</td>
				<td>:</td>
				<!--<td><input name="police_number" id="nama_denom" class="easyui-validatebox" style="width:100%" required="true"></input></td>-->
				<td>
					<select name="police_number" class="easyui-validatebox police_number" style="width: 100%" required="true">
						<option value="">- select police number -</option>
						
					</select>
				</td>
			</tr>
			<tr>
				<td>First Security</td>
				<td>:</td>
				<!--<td><input name="police_number" id="nama_denom" class="easyui-validatebox" style="width:100%" required="true"></input></td>-->
				<td>
					<select name="security_1" class="easyui-validatebox security_1" style="width: 100%" required="true">
						<option value="">- select first security -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Second Security</td>
				<td>:</td>
				<!--<td><input name="police_number" id="nama_denom" class="easyui-validatebox" style="width:100%" required="true"></input></td>-->
				<td>
					<select name="security_2" class="easyui-validatebox security_2" style="width: 100%" required="true">
						<option value="">- select second security -</option>
					</select>
				</td>
			</tr>
			<!--<tr>
				<td>Vehicle Type</td>
				<td>:</td>
				<td><input name="type" id="nama_denom" class="easyui-validatebox" style="width:100%" required="true"></input></td>
			</tr>
			<tr>
				<td>KM Status</td>
				<td>:</td>
				<td><input name="km_status" id="nama_denom" class="easyui-validatebox" style="width:100%" required="true"></input></td>
			</tr>
			<tr>
				<td>First Security</td>
				<td>:</td>
				<td><input name="security_1" id="nama_denom" class="easyui-validatebox" style="width:100%" required="true"></input></td>
			</tr>
			<tr>
				<td>Second Security</td>
				<td>:</td>
				<td><input name="security_2" id="nama_denom" class="easyui-validatebox" style="width:100%" required="true"></input></td>
			</tr>-->
		</table>
		<table class="dv-table" style="float:left;border:1px solid #ccc;padding:5px;margin-top:5px;width:400px">
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
		var page    = '<?=base_url()?>security/suggest';
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
		
		// jq341(".police_number").select2({no_results_text: "Oops, nothing found!"});
		jq341('.run_sheet_number').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'security/suggest'?>',
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
			var data = jq341(".js-example-basic-singleasd option:selected").text();
			array = data.split("-");
			
			$("#nama_bank").val(array[0]);
			$("#nama_lokasi").val(array[1]);
			$("#nama_sektor").val(array[2]);
		});
		
		
		jq341('.police_number').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'security/police_number'?>',
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
			// jq341(".security_2").select2("val", "");
			jq341(".security_2").val('').trigger('change')
			// var data = jq341(".js-example-basic-singleasd option:selected").text();
			// array = data.split("-");
			
			// $("#nama_bank").val(array[0]);
			// $("#nama_lokasi").val(array[1]);
			// $("#nama_sektor").val(array[2]);
		});
		
		
		jq341('.security_1').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'security/suggest_security'?>',
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
			// jq341(".security_2").select2("val", "");
			jq341(".security_2").val('').trigger('change')
			// var data = jq341(".js-example-basic-singleasd option:selected").text();
			// array = data.split("-");
			
			// $("#nama_bank").val(array[0]);
			// $("#nama_lokasi").val(array[1]);
			// $("#nama_sektor").val(array[2]);
		});
		
		jq341('.security_2').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'security/suggest_security2'?>',
				delay: 250,
				type: "POST",
				data: function(params) {
					return {
						search: params.term,
						id_cashtransit: '<?=$id?>',
						prev_id: jq341(".security_1 option:selected").val()
					}
				},
				processResults: function (data, page) {
					return {
						results: data
					};
				}
			}
		}).on('select2:select', function (evt) {
			// var data = jq341(".js-example-basic-singleasd option:selected").text();
			// array = data.split("-");
			
			// $("#nama_bank").val(array[0]);
			// $("#nama_lokasi").val(array[1]);
			// $("#nama_sektor").val(array[2]);
		});
		
	});
</script>
