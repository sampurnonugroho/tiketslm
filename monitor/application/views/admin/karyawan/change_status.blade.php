@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
			
	</div></div>

	<!-- Content -->
	<article class="container_12">
		
	
		<section class="grid_2"></section>
		<section class="grid_8">
			<div class="block-border">
			
				<form class="block-content form" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">					
					<input type="hidden" class="form-control" name="nik" value="<?php echo $nik;?>">
					
					<fieldset class="grey-bg required">
						<legend>Change Status Employee</legend>
						<p>
							<label for="simple-action">Status Employee</label>
							<?php echo form_dropdown('id_status', $dd_status, $id_status, ' id="id_status" required class="full-width"');?>
						</p>
						<button type="submit" style="float: right">Save</button>
						
						
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
	<script language="javascript" type="text/javascript">
		// $(document).on("change", "#id_departemen", function() {
			// alert("AAA");
		// });
	
		$(document).ready(function() {

			$("#id_departemen").change(function(){
				// Put an animated GIF image insight of content
				var id_departemen = $("#id_departemen").val();
				// alert(id_departemen);
				$.ajax({
					type: "POST",
					url: '<?php echo base_url().'select/select_bagian_departemen'?>',					
					data: {id_departemen: id_departemen},
					success: function(msg){
						// alert(msg);
						console.log(msg);
						if(msg=="") {
							$('#div-order').hide();
						} else {
							$('#div-order').show();
						}
						$('#div-order').html(msg);
					}, error: function(e) {
						alert(JSON.stringify(e));
					}
				});
			});   
		});
		
		// $(document).on("change", "#id_departemen", function() {
			// alert("AA");
		// });
	</script>
@endsection