@extends('layouts.master')

@section('content')
	<style type="text/css">
		form{
			margin:0;
			padding:0;
		}
		.dv-table td{
			border:0;
		}
		.dv-table input{
			border:1px solid #ccc;
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
	<style>
		body {
		  font-family: Verdana, sans-serif;
		  margin: 0;
		}

		* {
		  box-sizing: border-box;
		}

		.row > .column {
		  padding: 0 8px;
		}

		.row:after {
		  content: "";
		  display: table;
		  clear: both;
		}

		.column {
		  float: left;
		  width: 25%;
		}

		/* The Modal (background) */
		.modal {
		  display: none;
		  position: fixed;
		  z-index: 1;
		  padding-top: 100px;
		  left: 0;
		  top: 0;
		  width: 100%;
		  height: 100%;
		  overflow: auto;
		  background-color: black;
		}

		/* Modal Content */
		.modal-content {
		  position: relative;
		  background-color: #fefefe;
		  margin: auto;
		  padding: 0;
		  width: 90%;
		  max-width: 1200px;
		}

		/* The Close Button */
		.close {
		  color: white;
		  position: absolute;
		  top: 10px;
		  right: 25px;
		  font-size: 35px;
		  font-weight: bold;
		}

		.close:hover,
		.close:focus {
		  color: #999;
		  text-decoration: none;
		  cursor: pointer;
		}

		.mySlides {
		  display: none;
		}

		.cursor {
		  cursor: pointer;
		}

		/* Next & previous buttons */
		.prevZ,
		.nextZ {
		  cursor: pointer;
		  position: absolute;
		  top: 50%;
		  width: auto;
		  padding: 16px;
		  margin-top: -50px;
		  color: white;
		  font-weight: bold;
		  font-size: 20px;
		  transition: 0.6s ease;
		  border-radius: 0 3px 3px 0;
		  user-select: none;
		  -webkit-user-select: none;
		}

		/* Position the "next button" to the right */
		.next {
		  right: 0;
		  border-radius: 3px 0 0 3px;
		}

		/* On hover, add a black background color with a little bit see-through */
		.prev:hover,
		.next:hover {
		  background-color: rgba(0, 0, 0, 0.8);
		}

		/* Number text (1/3 etc) */
		.numbertext {
		  color: #f2f2f2;
		  font-size: 12px;
		  padding: 8px 12px;
		  position: absolute;
		  top: 0;
		}

		img {
		  margin-bottom: -4px;
		}

		.caption-container {
		  text-align: center;
		  background-color: black;
		  padding: 2px 16px;
		  color: white;
		}

		.demo {
		  opacity: 0.6;
		}

		.active,
		.demo:hover {
		  opacity: 1;
		}

		img.hover-shadow {
		  transition: 0.3s;
		}

		.hover-shadow:hover {
		  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
	</style>
	
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery.easyui.min.js"></script>
	
	
	<script src="<?=base_url()?>assets/select2/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>assets/select2/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>assets/select2/select2.min.js"></script>
	
	<!-- Content -->
	<article class="container_12">
		
		<section class="grid_12">
			<div class="block-border">
				<div class="block-content no-title dark-bg">
					<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION 
						<br>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?>]
					</p>
				</div>
			</div>
			<?php 
				if(!empty($data_summary)) {
					$data_ho = json_decode($data_summary[0]->data_handover);
				}
			?>
		
			<div class="preview_pdf" hidden>
				<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
				<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
			</div>
			<div class="widget_wrap preview_table">
				<div class="widget_content">
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									No
								</th>
								<th>
									Jenis
								</th>
								<th>
									Jumlah
								</th>
								<th>
									Keterangan
								</th>
								<th>
									Foto
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(!empty($data_summary)) {
									$no = 0;
									$idx_foto = 1;
									foreach($data_ho as $row): 
									$no++;
							?>
										<tr>
											<td><?=$no?></td>
											<td><?=$row->jenis?></td>
											<td><?=$row->jumlah?></td>
											<td><?=$row->keterangan?></td>
											<td>
												<?php if($row->foto!=="" && $row->foto!=="document_bast.jpg") { ?>
														<img src="http://pt-bijak.co.id/rest_api_dev_minggu/server/upload/<?=$wsid?>/<?=$row->foto?>?<?=rand()?>" style="transform: rotate(90deg);" width="100" height="100" onclick="openModal();currentSlide(<?=$idx_foto?>)" class="hover-shadow cursor"></img>
												<?php 	$idx_foto++; 
													  } else if($row->foto!=="" && $row->foto=="document_bast.jpg") { ?>
														<img src="http://pt-bijak.co.id/rest_api_dev_minggu/server/upload/<?=$wsid?>/document_bast_internal.jpg?<?=rand()?>" style="transform: rotate(90deg);" width="100" height="100" onclick="openModal();currentSlide(<?=$idx_foto?>)" class="hover-shadow cursor"></img>
														<img src="http://pt-bijak.co.id/rest_api_dev_minggu/server/upload/<?=$wsid?>/document_bast_external.jpg?<?=rand()?>" style="transform: rotate(90deg);" width="100" height="100" onclick="openModal();currentSlide(<?=$idx_foto+1?>)" class="hover-shadow cursor"></img>
												<?php 	$idx_foto++; 
													  } ?>
											</td>
										</tr>
							<?php 
									endforeach; 
								}
							?>
						</tbody>
						</table>
				</div>
			</div>
		</section>
	
		<div class="clear"></div>
		
		<div id="myModal" class="modal">
			<span class="close cursor" onclick="closeModal()">&times;</span>
			<div class="modal-content">
				<?php 
					$no = 0;
					$idx_fotox = 1;
					foreach($data_ho as $row): 
					$no++;
				?>
						<?php if($row->foto!=="" && $row->foto!=="document_bast.jpg") { ?>
							<div class="mySlides">
								<div class="numbertext"><?=($idx_fotox+1)?></div>
								<img src="http://pt-bijak.co.id/rest_api_dev_minggu/server/upload/<?=$wsid?>/<?=$row->foto?>?<?=rand()?>" style="width:100%; height:60%; transform: rotate(90deg);">
							</div>
						<?php 	$idx_fotox++;	  
						
						} else if($row->foto!=="" && $row->foto=="document_bast.jpg") { ?>
							<div class="mySlides">
								<div class="numbertext"><?=($idx_fotox+1)?></div>
								<img src="http://pt-bijak.co.id/rest_api_dev_minggu/server/upload/<?=$wsid?>/document_bast_internal.jpg?<?=rand()?>" style="width:100%; height:60%; transform: rotate(90deg);">
							</div>
							<div class="mySlides">
								<div class="numbertext"><?=($idx_fotox+2)?></div>
								<img src="http://pt-bijak.co.id/rest_api_dev_minggu/server/upload/<?=$wsid?>/document_bast_external.jpg?<?=rand()?>" style="width:100%; height:60%; transform: rotate(90deg);">
							</div>
						<?php 	$idx_fotox++;	
						} ?>
				<?php 
					endforeach; 
				?>

				<a class="prevZ" onclick="plusSlides(-1)">&#10094;</a>
				<a class="nextZ" onclick="plusSlides(1)">&#10095;</a>

				<div class="caption-container">
					<p id="caption"></p>
				</div>
			</div>
		</div>
		
	</article>
	<script>
		function openModal() {
			document.getElementById("myModal").style.display = "block";
		}

		function closeModal() {
			document.getElementById("myModal").style.display = "none";
		}

		var slideIndex = 1;
		showSlides(slideIndex);

		function plusSlides(n) {
			showSlides(slideIndex += n);
		}

		function currentSlide(n) {
			showSlides(slideIndex = n);
		}

		function showSlides(n) {
			var i;
			var slides = document.getElementsByClassName("mySlides");
			var dots = document.getElementsByClassName("demo");
			var captionText = document.getElementById("caption");
			if (n > slides.length) {
				slideIndex = 1
			}
			if (n < 1) {
				slideIndex = slides.length
			}
			for (i = 0; i < slides.length; i++) {
				slides[i].style.display = "none";
			}
			for (i = 0; i < dots.length; i++) {
				dots[i].className = dots[i].className.replace(" active", "");
			}
			slides[slideIndex - 1].style.display = "block";
			dots[slideIndex - 1].className += " active";
			captionText.innerHTML = dots[slideIndex - 1].alt;
		}
	
	
		$(document).on('click', '#detail_preview', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf/qrcode";
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		$(document).on('click', '#close_preview', function(){ 
			$(".preview_pdf").hide();
			$(".preview_table").show();
		});
	</script>
@endsection