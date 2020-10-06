
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

	.datagrid-header td, .datagrid-body td, .datagrid-footer td {
		border-width: 0 0 0 0;
		border-style: dotted;
		margin: 0;
		padding: 0;
	}
</style>
<form method="post">
	<div>
		<table class="dv-table" style="float:left;padding:5px;margin-top:5px;">
			<tr>
				<td colspan="3" style="text-align: center"><br></td>
			</tr>
			<tr>
				<td>NOMOR BOC</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="id_cashtransit" type="hidden" value="<?=$id?>" readonly>
					<input name="nomor_boc" type="text" value="<?=$no_boc?>" class="easyui-validatebox" readonly>
				</td>
			</tr>
			<tr>
				<td>METODE TRANSAKSI</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<select name="metode" id="metode" class="easyui-validatebox js-example-basic-singleSSS" style="width: 150px" required="required">
						<option value="">- select type -</option>
						<option value="cp">CASH PICKUP</option>
						<option value="cd">CASH DELIVERY</option>
						<!--<option value="ctc">CASH TO CASH</option>-->
					</select>
				</td>
			</tr>
			<tr class="group_general" hidden>
				<td>JENIS TRANSAKSI</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<select name="jenis" class="easyui-validatebox js-example-basic-singleSSS" style="width: 100%" required="required">
						<option value="">- select type -</option>
						<option value="stc">STC</option>
						<option value="bbc">BBC</option>
						<option value="cos">BBC</option>
					</select>
				</td>
			</tr>
			<tr class="group_cp" hidden>
				<td>CLIENT PENGIRIM</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<select name="client_pengirim" class="easyui-validatebox client_pengirim_<?=$index?>">
						<option value="">- select client -</option>
					</select>
				</td>
			</tr>
			<tr class="group_general" hidden>
				<td>CLIENT PENERIMA</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<select name="client_penerima" class="easyui-validatebox client_penerima_<?=$index?>" required="required">
						<option value="">- select client -</option>
					</select>
				</td>
			</tr>
			<tr class="action_general">
				<td></td>
				<td></td>
				<td>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="save1(this)">Save</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel1(this)">Cancel</a>
				</td>
			</tr>
		</table>
		<table class="group_detail" hidden class="dv-table" style="float:left;padding:5px;margin-top:5px;">
			<tr>
				<td colspan="3" style="text-align: center">UANG KERTAS</td>
			</tr>
			<tr>
				<td>100,000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_100k" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>50,000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_50k" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>20,000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_20k" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>10,000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_10k" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>5,000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_5k" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>2,000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_2k" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>1,000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="kertas_1k" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
		</table>
		<table class="group_detail" hidden class="dv-table" style="float:left;padding:5px;margin-top:5px;">
			<tr>
				<td colspan="3" style="text-align: center">UANG LOGAM</td>
			</tr>
			<tr>
				<td>1,000</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_1k" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>500</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_500" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>200</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_200" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>100</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_100" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
				</td>
			</tr>
			<tr>
				<td>50</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="logam_50" class="easyui-validatebox easyui-textbox"></input>
					<!-- <input name="div_100" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2016'" placeholder="Emisi 2016" class="easyui-validatebox easyui-textbox" type="text">
					<input name="div_50" style="width: 100px; height: 28px;" value="" data-options="prompt:'Emisi 2014'" placeholder="Emisi 2014" class="easyui-validatebox easyui-textbox" type="text"> -->
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
	console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );

	jq341(document).ready(function()
	{
		jq341(document).on("change", "#metode", function(e) {
			that = $(this);
			value = that.val();
			
			if(value=="cp") {
				jq341(".group_cp").show();
				jq341(".group_general").show();
				jq341(".group_detail").hide();
				jq341(".action_general").show();
			} else if(value=="cd") {
				jq341(".group_cp").hide();
				jq341(".group_general").show();
				jq341(".group_detail").show();
				jq341(".action_general").hide();
			} else if(value=="ctc") { 
				jq341(".group_cp").hide();
				jq341(".group_general").show();
				jq341(".group_detail").show();
				jq341(".action_general").hide();
			} else {
				jq341(".group_cp").hide();
				jq341(".group_general").hide();
				jq341(".group_detail").hide();
				jq341(".action_general").hide();
			}
		});
		
		jq341(".js-example-basic-singleSSS").select2({no_results_text: "Oops, nothing found!"}); 
		jq341(".run_sheet_number").select2({no_results_text: "Oops, nothing found!"}).on('select2:select', function (evt) {
			var id = jq341(".run_sheet_number option:selected").val();
			
			var page    = '<?=base_url()?>cashtransit/get_suggest';
			jq341.ajax({
				url: page,
				data : 'id='+id,
				type: "post", 
				dataType: "html",
				timeout: 10000,
				success: function(response){
					response = JSON.parse(response);
					
					$("#nama_bank").textbox('setValue', response.bank);
					$("#nama_lokasi").textbox('setValue', response.lokasi);
					$("#nama_sektor").textbox('setValue', response.sektor);
					$("#nama_denom").textbox('setValue', response.denom);
				}
			});
		});
		
		
		jq341('.client_pengirim_<?=$index?>').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'Cashtransit/suggest_client1'?>',
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
			jq341(".client_penerima_<?=$index?>").val('').trigger('change')
		});
		
		jq341('.client_penerima_<?=$index?>').select2({
			tokenSeparators: [','],
			width: '100%',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'Cashtransit/suggest_client2'?>',
				delay: 250,
				type: "POST",
				data: function(params) {
					return {
						search: params.term,
						prev_id: jq341(".client_pengirim_<?=$index?> option:selected").val()
					}
				},
				processResults: function (data, page) {
					return {
						results: data
					};
				}
			}
		}).on('select2:select', function (evt) {
			// jq341(".custodian_2").val('').trigger('change')
		});
	});
</script>
