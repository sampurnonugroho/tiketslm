<?=$bank?>
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
					<select name="wsid" class="easyui-validatebox wsid" required="required">
						<option value="">- select wsid -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>LOKASI</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="lokasi" type="text" class="easyui-validatebox easyui-textbox lokasi" required="required">
				</td>
			</tr>
			<tr>
				<td>TYPE</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<!--<input name="type" type="text" class="easyui-validatebox">-->
					<input name="type" type="text" class="easyui-validatebox easyui-textbox type" required="required">
				</td>
			</tr>
			<tr>
				<td>PAKET</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="paket" type="text" class="easyui-validatebox easyui-textbox paket" required="required">
				</td>
			</tr>
			<tr>
				<td>DENOMINASI</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="denom" type="text" class="easyui-validatebox easyui-textbox denom" required="required">
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
			<tr>
				<td>LIMIT MINIMUM</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="tgl_min_dari" type="text" class="easyui-validatebox easyui-textbox tgl_min_dari" placeholder="Dari Tanggal" required="required">
					<input name="tgl_min_hingga" type="text" class="easyui-validatebox easyui-textbox tgl_min_hingga" placeholder="Hingga Tanggal" required="required">
					<input name="limit_min" type="text" class="easyui-validatebox easyui-textbox limit_min" placeholder="Nominal Limit" required="required">
				</td>
			</tr>
			<tr>
				<td>LIMIT MAXIMUM</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="tgl_max_dari" type="text" class="easyui-validatebox easyui-textbox tgl_max_dari" placeholder="Dari Tanggal" required="required">
					<input name="tgl_max_hingga" type="text" class="easyui-validatebox easyui-textbox tgl_max_hingga" placeholder="Hingga Tanggal" required="required">
					<input name="limit_max" type="text" class="easyui-validatebox easyui-textbox limit_max" placeholder="Nominal Limit" required="required">
				</td>
			</tr>
		</table>
		<table class="dv-table" style="float:left;border:0px solid #ccc;padding:5px;margin-top:5px;width:200px">
			<tr>
				<td>CASSETTE</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="ctr" type="text" class="easyui-validatebox easyui-textbox ctr" required="required">
				</td>
			</tr>
			<tr>
				<td>DIVERT</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="reject" type="text" class="easyui-validatebox easyui-textbox reject" required="required">
				</td>
			</tr>
			<tr>
				<td>INTERVAL</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="interval_isi" type="text" class="easyui-validatebox easyui-textbox interval_isi" required="required">
				</td>
			</tr>
			<tr>
				<td>SIFAT</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="sifat" type="text" class="easyui-validatebox easyui-textbox sifat" required="required">
				</td>
			</tr>
			<tr>
				<td>TANGGAL EFEKTIF</td>
				<td>:</td>
				<td style="border: 0px; padding: 10px 15px 5px 0px">
					<input name="tgl_efektif" id="dd" type="text" class="easyui-datebox easyui-textbox tgl_efektif" data-options="formatter:myformatter,parser:myparser" required="required">
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
	
	jq341 = jQuery.noConflict();
	console.log( "<h3>After $.noConflict(true)</h3>" );
	console.log( "2nd loaded jQuery version (jq162xx): " + jq341.fn.jquery + "<br>" );

	jq341(document).ready(function()
	{
		// jq341('.wsid').select2({
			// tokenSeparators: [','],
			// width: '100%',
			// ajax: {
				// dataType: 'json',
				// url: '<?php echo base_url().'handover_out/get_data_client/'?>',
				// delay: 250,
				// type: "POST",
				// data: function(params) {
					// return {
						// search: params.term,
						// bank: '<?=$bank?>'
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
		
		jq341('.wsid').select2({
			ajax: {
				url: '<?php echo base_url().'handover_out/get_data_client/'?>',
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						search: params.term,
					};
				},
				processResults: function(data, params) {
					return {
						results: data
					};
				},
				cache: true
			},
			placeholder: 'Search for a repository',
			// templateResult: formatRepo,
			templateSelection: formatRepoSelection
		}).on('select2:select', function (evt) {
			// var data = jq341(".wsid option:selected").text();
			
			// console.log(evt);
		});

		function formatRepo(repo) {
			if (repo.loading) {
				return repo.text;
			}
			var $container = $("<div class='select2-result-repository clearfix'>" + "<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" + "<div class='select2-result-repository__meta'>" + "<div class='select2-result-repository__title'></div>" + "<div class='select2-result-repository__description'></div>" + "<div class='select2-result-repository__statistics'>" + "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" + "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" + "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" + "</div>" + "</div>" + "</div>");
			$container.find(".select2-result-repository__title").text(repo.full_name);
			$container.find(".select2-result-repository__description").text(repo.description);
			$container.find(".select2-result-repository__forks").append(repo.forks_count + " Forks");
			$container.find(".select2-result-repository__stargazers").append(repo.stargazers_count + " Stars");
			$container.find(".select2-result-repository__watchers").append(repo.watchers_count + " Watchers");
			return $container;
		}

		function formatRepoSelection(repo) {
			if(repo.id!=="") {
				// jq341('input[name ="lokasi"]').textbox('setValue', repo.lokasi);
				$(".lokasi").textbox('setValue', repo.lokasi);
				$(".type").textbox('setValue', repo.type);
				$(".paket").textbox('setValue', repo.paket);
				$(".denom").textbox('setValue', repo.denom);
				$(".tgl_min_dari").textbox('setValue', repo.tgl_min_dari);
				$(".tgl_min_hingga").textbox('setValue', repo.tgl_min_hingga);
				$(".tgl_max_dari").textbox('setValue', repo.tgl_max_dari);
				$(".tgl_max_hingga").textbox('setValue', repo.tgl_max_hingga);
				$(".ctr").textbox('setValue', repo.ctr);
				$(".reject").textbox('setValue', repo.reject);
				$(".limit_min").textbox('setValue', repo.limit_min);
				$(".limit_max").textbox('setValue', repo.limit_max);
				$(".interval_isi").textbox('setValue', repo.interval_isi);
				$(".sifat").textbox('setValue', repo.sifat);
				$(".tgl_efektif").textbox('setValue', repo.tgl_efektif);
			}
			return repo.wsid || repo.lokasi;
		}
	});

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
