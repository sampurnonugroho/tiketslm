

<form method="post">
	<div>
		<table class="dv-table3" style="float:left;padding:50px;margin-top:2px;">
			<tr>
				<td>&nbsp ASSIGN TO</td>
				<td>&nbsp </td>
				<td>
					<input name="id_ticket" type="hidden" readonly>
					<select name="teknisi_2" class="easyui-validatebox run_sheet_number3" style="width: 100%" required="true">
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
				url: '<?php echo base_url().'ticket/select_problem'?>',
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
				url: '<?php echo base_url().'ticket_slm/select_slm'?>',
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
