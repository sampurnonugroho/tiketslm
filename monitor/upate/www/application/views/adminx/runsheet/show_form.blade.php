<table id="dg" class="easyui-datagrid" style="width:100%;height:auto"
		data-options="rownumbers:false,singleSelect:true,url:'<?=base_url()?>runsheet/get_data2/<?=$id_cashtransit?>',method:'get'" toolbar="#toolbar" pagination="false">
	<thead>
		<tr>
			<th data-options="field:'run_number',width:130">Run Sheet Area</th>
			<th data-options="field:'type',width:150">Vehicle Type</th>
			<th data-options="field:'police_number',width:150">Police Number</th>
			<th data-options="field:'km_status',width:150">KM Status</th>
			<th data-options="field:'security_1',width:150">First Security</th>
			<th data-options="field:'security_2',width:150">Second Security</th>
		</tr>
	</thead>
</table>