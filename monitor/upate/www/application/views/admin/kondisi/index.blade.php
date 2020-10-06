@extends('layouts.master')

@section('content')
	<!-- Content -->
	<article class="container_12">
	<section class="grid_12">
		<div class="block-border"><form class="block-content form" id="complex_form" method="post" action="#">
			<h1>Keterangan Severity Level :</h1>
			
			<div class="block-controls">
			</div>
			
			<div class="columns">
				<div class="col200pxL-left">
					
					<ul class="side-tabs js-tabs same-height">
						<li><a href="#tab-level1" title="Global properties">Severity Level 1</a></li>
						<li><a href="#tab-level2" title="Language settings">Severity Level 2</a></li>
						<li><a href="#tab-level3" title="Relations">Severity Level 2</a></li>
					</ul>
					
				</div>
				<div class="col200pxL-right">
					
					
					<div id="tab-level1" class="tabs-content">
						<p>
							Dimaksudkan dengan terjadinya peristiwa dimana server produksi milik customer ataupun sistem misi kritikal (mission critical) “down” atau mati, dan tidak ada “workaround” yg bisa didapatkan ataupun tersedia dalam waktu dekat. Seluruh atau porsi yang penting dari data misi kritikal (mission critical data) milik customer berada dalam resiko yg besar untuk hilang atau loss, korup atau corruption. Customer mendapatkan bahwa beberapa porsi penting dari service tidak bisa berfungsi seperti kondisi normal
						</p>
					</div>
					
					<div id="tab-level2" class="tabs-content">
						<p>
							Dimaksudkan dengan terjadinya peristiwa yang menyebabkan fungsi2 utama (major) tidak berfungsi sebagian, contoh: 1.operasi-operasi dapat berlanjut meski dalam batasan-batasan tertentu, namun dalam jangka panjang produktifitas akan terganggu 2.Sebuah “workaround” berhasil ditemukan, didapatkan atau teridentifikasi dalam waktu yang singkat
						</p>
					</div>
					
					<div id="tab-level3" class="tabs-content">
						<p>
							Dimaksudkan terjadinya issue-issue yang bersifat parsial, sebahagian, tidak bersifat kritis, dan bukan terjadi pada fungsi-fungsi utama (major). Pada severity 3, beberapa komponen tidak berfungsi, namun users tetap bisa menggunakan aplikasi, meski hanya pada fungsi-fungsi utama saja misalnya Kita bisa pula mendeskripsikan severity 3 sebagai pengaruh dari fungsi-fungsi minor Fungsi minor tidak berfungsi, tersedia workaround Sebagian, fungsi-fungsi tidak kritikal tidak bisa menjalankan tugasnya ataupun Minor bugs
						</p>
					</div>
					
				</div>
			</div>
			
		</form></div>
	</section>
	<section class="grid_12">
			<div class="block-border" align="center"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>Data Severity Level</h1>
				
					
					<div class="block-border"><div class="block-content no-title dark-bg">
					<div id="control-bar"><div class="container_16">
					<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
					</div>
					<div class="float-right">
							<button type="button" onClick="window.location.href='<?=base_url()?>kondisi/add'">Add Data</button>
					</div>
					<center>
					<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
					</center>
					<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>PORTAL MONITORING SYSTEM & SLA REPORT  
					<br>[DATA SEVERITY LEVEL]
					</p>
						
					</div>
					</div>	
					</div>
					</div>
							
				
				
				<table class="table sortable2 no-margin" cellspacing="0" width="100%">
				
					<thead>
						<tr>
							<th class="black-cell"><span class="loading"></span></th>
							<th scope="col">
								<span class="column-sort">
									<a href="javascript:void(0)" title="Sort up" class="sort-up"></a>
									<a href="javascript:void(0)" title="Sort down" class="sort-down"></a>
								</span>
								Severity Level
							</th>
							<th scope="col">
								Waktu Respon
							</th>
							<th scope="col">
								Aksi
							</th>
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							if(!empty($datakondisi))
							foreach($datakondisi as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->nama_kondisi;?></td>
							<td><?php echo $row->waktu_respon;?></td>
							<td>
								<button type="button" class='button green' onClick="window.location.href='<?php echo base_url();?>kondisi/edit/<?php echo $row->id_kondisi;?>'" title='Edit'><span class='smaller'>Edit</span></button>
								<button type="button" class='button red' onClick="openDelete('<?php echo $row->id_kondisi;?>', '<?php echo base_url();?>kondisi/delete')" title='Delete'><span class='smaller'>Del</span></button>
							</td>
						</tr>
						<?php 
							endforeach; 
						?>
					</tbody>
				
				</table>
					
			</form></div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection