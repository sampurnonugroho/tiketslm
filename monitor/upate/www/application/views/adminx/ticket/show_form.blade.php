

<form method="post">
	<div>
		<table class="dv-table" style="float:left;padding:50px;margin-top:2px;">
			<tr>
				<td>ID</td>
				<td>&nbsp </td>
				<!--<td>
					<input name="id_cashtransit" type="hidden" value="<?=$id?>" readonly>
					<input name="id_bank" id="src" onkeypress="suggest(this.value);" class="easyui-validatebox easyui-textbox"></input><div id="suggest"></div>
				</td>-->
				<td>
					<input name="id_cashtransit" type="hidden" readonly>
					<select name="id_bank" class="easyui-validatebox run_sheet_number" style="width:100%" required="true">
						<option value="">- select client -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>BRANCH</td>
				<td>&nbsp </td>
				<td><input name="bank" id="nama_branch" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 30px; line-height: 45px; width: 100%;" class="easyui-validatebox easyui-textbox" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>GA</td>
				<td>&nbsp </td>
				<td><input name="sektor" id="nama_ga" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 30px; line-height: 45px; width: 100%;" class="easyui-validatebox easyui-textbox" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>BANK</td>
				<td>&nbsp </td>
				<td><input name="bank" id="nama_bank" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 30px; line-height: 45px; width: 100%;" class="easyui-validatebox easyui-textbox" disabled="disabled"></input></td>
			</tr>
			</table>
		<table class="dv-table2" style="float:left;padding:50px;margin-top:2px;">
			<tr>
				<td>&nbsp ACT</td>
				<td>&nbsp </td>
				<td><input name="act" id="nama_act" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 30px; line-height: 45px; width: 100%;" class="easyui-validatebox easyui-textbox" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>&nbsp BRAND</td>
				<td>&nbsp </td>
				<td><input name="brand" id="nama_brand" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 30px; line-height: 45px; width: 100%;" class="easyui-validatebox easyui-textbox" disabled="disabled"></input></td>
			</tr>
		
			<tr>
				<td>&nbsp MODEL</td>
				<td>&nbsp </td>
				<td><input name="model" id="nama_model" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 30px; line-height: 45px; width: 100%;" class="easyui-validatebox easyui-textbox" disabled="disabled"></input></td>
			</tr>
			<tr>
				<td>&nbsp LOCATION</td>
				<td>&nbsp </td>
				<td><input name="lokasi" id="nama_location" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 30px; line-height: 45px; width: 100%;" class="easyui-validatebox easyui-textbox" disabled="disabled"></input></td>
			</tr>	
		</table>
		<table class="dv-table3" style="float:left;padding:50px;margin-top:2px; width: 300px">
			<tr>
				<td>&nbsp EMAIL TIME</td>
				<td>&nbsp </td>
				<td><input name="email_time" id="nama_location" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 30px; line-height: 45px; width: 100%;" class="easyui-validatebox easyui-textbox"></input></td>
			</tr>	
			<tr>
				<td>&nbsp PROBLEM TYPE</td>
				<td>&nbsp </td>
				<td>
					<select multiple name="problem_type[]" class="easyui-validatebox run_sheet_number2" style="width: 100%" required="true">
						<option value="">- select problem -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp ASSIGN TO</td>
				<td>&nbsp </td>
				<td>
					<select name="teknisi_1" class="easyui-validatebox run_sheet_number3" style="width: 100%" required="true">
						<option value="">- select -</option>
					</select>
				</td>
			</tr>	
			<tr>
				<td>&nbsp OPERATION</td>
				<td>&nbsp </td>
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
	jq341 = jQuery.noConflict();
	console.log( "<h3>After $.noConflict(true)</h3>" );
	console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
	jq341(document).ready(function()
	{
		jq341('.run_sheet_number').select2({
			tokenSeparators: [','],
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'select/select_client'?>',
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
			var id = jq341(".run_sheet_number option:selected").val();
			
			$.ajax({url: '<?php echo base_url().'ticket/get_data_client'?>', data: {id: id}, success: function(result){
				result = JSON.parse(result);
				
				$("#nama_branch").textbox('setValue', result.branch);
				$("#nama_ga").textbox('setValue', result.ga);
				$("#nama_bank").textbox('setValue', result.bank);
				$("#nama_act").textbox('setValue', result.act);
				$("#nama_brand").textbox('setValue', result.brand);
				$("#nama_model").textbox('setValue', result.model);
				$("#nama_location").textbox('setValue', result.location);
			}});
		});
		
		jq341('.run_sheet_number2').select2({
			tokenSeparators: [','],
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'ticket/select_problem_flm'?>',
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
		});
		
		jq341('.run_sheet_number3').select2({
			tokenSeparators: [','],
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url().'ticket/select_flm'?>',
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
		});
	});
</script>
