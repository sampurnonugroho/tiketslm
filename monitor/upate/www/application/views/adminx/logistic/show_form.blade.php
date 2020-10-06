<style>
	fieldset{margin-bottom: 1em;width:300px;}
	#suggest{position:absolute;z-index:5;border-left:silver 1px solid;padding:0 0 0 10px;background-color:#ebebeb}
	span.pilihan{display:block;cursor:pointer;padding:5px}
	.easyui-validatebox {
		margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 28px; line-height: 28px; width: 146px;
	}
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
			<?php
				foreach($inventory as $r) {
			?>
			<tr>
				<td><?=$r->name?></td>
				<td>:</td>
				<td>
					<input name="inventory[<?=$r->id?>]" style="height: 28px" width="100%" class="easyui-validatebox easyui-numberbox easyui-textbox" data-options="min:0,max:<?=(($r->qty)-($r->used))?>,precision:0" required="true"></input>
				</td>
			</tr>
			<?php
				}
			?>
			<tr hidden>
				<td>TES</td>
				<td>:</td>
				<td>
					<select class="js-example-placeholder-multiple" style="width: 10px" multiple="multiple"></select>
				</td>
			</tr>
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
		var page    = '<?=base_url()?>logistic/suggest';
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

	function matchCustom(params, data) {
		// If there are no search terms, return all of the data
		if ($.trim(params.term) === '') {
			return data;
		}

		// Do not display the item if there is no 'text' property
		if (typeof data.text === 'undefined') {
			return null;
		}

		// `params.term` should be the term that is used for searching
		// `data.text` is the text that is displayed for the data object
		if (data.text.indexOf(params.term) > -1) {
			var modifiedData = $.extend({}, data, true);
			modifiedData.text += ' (matched)';

			// You can return modified objects from here
			// This includes matching the `children` how you want in nested data sets
			return modifiedData;
		}

		// Return `null` if the term should not be displayed
		return null;
	}

	jq341(document).ready(function()
	{
		
		// jq341(".js-example-placeholder-multiple").select2({
			// matcher: matchCustom,
			// tokenSeparators: [','],
			// width: '100%',
			// ajax: {
				// dataType: 'json',
				// url: '<?php echo base_url().'logistic/get_data_logistic'?>',
				// delay: 100,
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
		// });
		
		// $(document.body).on("change", ".js-example-placeholder-multiple", function(){
			// alert(this.value);
		// });
		
		jq341('.run_sheet_number').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'logistic/suggest_data_client'?>',
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
		
		jq341(".inventory").select2({no_results_text: "Oops, nothing found!", width: '100%'}); 
	});
</script>
