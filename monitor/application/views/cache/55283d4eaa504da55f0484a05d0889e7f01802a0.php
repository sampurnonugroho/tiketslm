<?php $__env->startSection('content'); ?>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/easyui2/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/easyui2/themes/icon.css">
	<style type="text/css">
		// form{
			margin:0;
			padding:0;
		}
		.dv-table td{
			border:0;
		}
		.dv-table input{
			border:1px solid #ccc;
		}
		.focus {
			border-color:red;
		}
		#preview {
			float: right; 
			height: 803px; 
			width: 100%; 
			border: 1px solid #666; 
			-moz-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			-webkit-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		}
	</style>
	
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/datagrid-detailview.js"></script>
	
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>
	<script src="<?=base_url()?>assets/notify.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		jq341 = jQuery.noConflict();
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "1nd loaded jQuery version ($): " + $.fn.jquery + "<br>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
		// $ = jq341;
	
		$(function(){
			$('#dg').datagrid({
				view: detailview,
				detailFormatter:function(index,row){
					return '<div class="ddv"></div>';
				},
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					ddv.panel({
						border:false,
						cache:true,
						// href:'show_form.php?index='+index,
						href:'<?=base_url()?>handover/show_form?index='+index+'&id='+<?=$id?>,
						onLoad:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
							$('#dg').datagrid('selectRow',index);
							$('#dg').datagrid('getRowDetail',index).find('form').form('load',row);
						}
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});
		});
		function saveItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			console.log(row);
			var url = row.isNewRecord ? '<?=base_url()?>handover/save_data' : '<?=base_url()?>handover/update_data?id='+row.id;
			console.log(url);
			$('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					console.log(data);
					jq341.notify("Data Handover, Berhasil Disimpan", "success");
					data = eval('('+data+')');
					data.isNewRecord = false;
					$('#dg').datagrid('collapseRow',index);
					$('#dg').datagrid('updateRow',{
						index: index,
						row: data
					});
				},
				error: function(e) {
					console.log(e)
				}
			});
		}
		function cancelItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			if (row.isNewRecord){
				$('#dg').datagrid('deleteRow',index);
			} else {
				$('#dg').datagrid('collapseRow',index);
			}
		}
		function destroyItem(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove this data?',function(r){
					if (r){
						var index = $('#dg').datagrid('getRowIndex',row);
						console.log(row);
						$.post('<?=base_url()?>handover/delete_data',{id:row.id},function(){
							$('#dg').datagrid('deleteRow',index);
						});
					}
				});
			}
		}
		function newItem(){
			$('#dg').datagrid('appendRow',{isNewRecord:true});
			var index = $('#dg').datagrid('getRows').length - 1;
			$('#dg').datagrid('expandRow', index);
			$('#dg').datagrid('selectRow', index);
		}
		
		function showButton(val,row){
			if (row["status"]=="done") {
				var s = '<div id="id" hidden>'+row["wsida"]+'</div>'+
						'<span style="margin-right: 5px"><a class="button" href="#" id="print_bast_done" title="Edit">BAST</a></span>'+
						'<span><a class="button" href="#" id="print_qrcode" title="Edit">QRCODE</a></span>';
				return s;
			} else {
				// print_bast
				// print_bast_done
				// print_qrcode
				var s = '<div id="id" hidden>'+row["wsida"]+'</div>'+
						'<span style="margin-right: 5px"><a class="button" href="#" id="print_bast" title="Edit">BAST</a></span>'+
						'<span><a class="button" href="#" id="print_qrcode" title="Edit">QRCODE</a></span>';
				return s;
			}
		}
	</script>

	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.location.href='<?=base_url()?>handover'"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
	</div></div>
	<!-- End control bar -->
	
	<div class="preview_pdf" hidden>
		<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
		<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
	</div>
	
	<article class="container_12 preview_table">
		<section class="grid_12">
			<table id="dg" class="easyui-datagrid" title="HANDOVER DETAIL" style="width:100%;height:550px"
				data-options="
					rownumbers:true,
					singleSelect:true,
					url:'<?=base_url()?>handover/get_data/<?=$id?>',
					method:'get',
					rowStyler: function(index,row){
						// console.table(row);
						if (row.wsid!==null){
							return 'background-color:#e6efff;color:black;font-weight:normal;';
						} else {
							return 'background-color:#ffe48d;color:black;font-weight:normal;';
						}
					}" toolbar="#toolbar" pagination="true">
				<!--<thead frozen="false">
					<tr>
						<th data-options="field:'metode',width:130">METODE</th>
						<th data-options="field:'pengirim',width:110">PENGIRIM</th>
						<th data-options="field:'penerima',width:110">PENERIMA</th>
					</tr>
				</thead>-->
				<thead>
					<tr>
						<th data-options="field:'wsida',width:150,align:'center',formatter:showButton">ACTION</th>
						<!--<th data-options="field:'id',width:80,align:'center'">ID</th>
						<th data-options="field:'id_detail',width:120,align:'center'">ID DETAIL</th>-->
						<th data-options="field:'wsid',width:120,align:'center'">WSID</th>
						<th data-options="field:'bank',width:120,align:'center'">BANK</th>
						<th data-options="field:'lokasi',width:220,align:'center'">ALAMAT</th>
						<th data-options="field:'type',width:120,align:'center'">TYPE</th>
						<th data-options="field:'paket',width:120,align:'center'">PAKET</th>
						<th data-options="field:'denom',width:120,align:'center'">DENOM</th>
						<th data-options="field:'sifat',width:120,align:'center'">SIFAT</th>
						<th data-options="field:'tgl_efektif',width:120,align:'center'">TANGGAL EFEKTIF</th>
					</tr>
				</thead>
			</table>

			<!--<div id="toolbar">
				<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">New</a>
				<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">Destroy</a>
			</div>-->
		</section>
		<div class="clear"></div>
	</article>
	
	<script>
		jq341(document).on('click', '#print_qrcode', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>qr/index/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		jq341(document).on('click', '#print_bast', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf_fix/bast";
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		jq341(document).on('click', '#print_bast_done', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
// 			alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf_fix/bast_done/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		jq341(document).on('click', '#close_preview', function(){ 
			$(".preview_pdf").hide();
			$(".preview_table").show();
		});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>