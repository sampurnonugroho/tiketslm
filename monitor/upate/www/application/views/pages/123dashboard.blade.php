@extends('layouts.master')

@section('content')
	<!-- Content -->
	<article class="container_12">
	
	<section class="grid_12">
			<div class="block-border"><form class="block-content form" id="complex_form" method="post" action="#">
				<h1>Main Dashboard & Summary Information</h1>
				
				<div class="block-controls">
					
					<ul class="controls-tabs js-tabs">
						<li class="current"><a href="#tab-gi" title="General Summary Information & Properties"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Bar-Chart.png" width="24" height="24"></a></li>
						<li><a href="#tab-op" title="Comments"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Comment.png" width="24" height="24"></a></li>
						<li><a href="#tab-faq" title="Medias"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Picture.png" width="24" height="24"></a></li>
						<li><a href="#tab-hds" title="Users"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Profile.png" width="24" height="24"></a></li>
						<li><a href="#tab-dla" title="Informations"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Info.png" width="24" height="24"></a></li>
						<li><a href="#tab-sl" title="Informations"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Info.png" width="24" height="24"></a></li>
					</ul>
					
				</div>
				<div class="columns">
					<div class="col200pxL-left">
						
						<h2>Portal Monitoring System & SLA Report</h2>
						
						<ul class="side-tabs js-tabs same-height">
							<li><a href="#tab-gi" title="General Summary Information & Properties">General Summary Information & Properties</a></li>
							<li><a href="#tab-op" title="Operational Procedures & User Guide">Operational Procedures & Manual Instruction</a></li>
							<li><a href="#tab-faq" title="Frequently Asked Question (FAQ)">Frequently Asked Question (FAQ)</a></li>
							
							<li><a href="#tab-hds" title="Operational Procedures & User Guide">Historycal Data & System Log</a></li>
							<li><a href="#tab-dla" title="Operational Procedures & User Guide">Data Load Analytics</a></li>
							<li><a href="#tab-sl" title="System Licences">System Licences</a></li>
							
						</ul>
					
					<div class="block-border grid_10">
					<div class="block-content no-title dark-bg"><p align="center"><b>Calendar</b></p>
					<div class="mini-calendar">
						<div class="calendar-controls">
							<a href="javascript:void(0)" class="calendar-prev" title="Previous month"><img src="<?=base_url()?>constellation/assets/images/cal-arrow-left.png" width="16" height="16"></a>
							<a href="javascript:void(0)" class="calendar-next" title="Next month"><img src="<?=base_url()?>constellation/assets/images/cal-arrow-right.png" width="16" height="16"></a>
							June 2019
						</div>
						
						<table cellspacing="0">
							<thead>
								<tr>
									<th scope="col" class="week-end">S</th>
									<th scope="col">M</th>
									<th scope="col">T</th>
									<th scope="col">W</th>
									<th scope="col">T</th>
									<th scope="col">F</th>
									<th scope="col" class="week-end">S</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="week-end other-month">28</td>
									<td class="other-month">29</td>
									<td class="other-month">30</td>
									<td class="other-month">31</td>
									<td><a href="javascript:void(0)">1</a></td>
									<td><a href="javascript:void(0)">2</a></td>
									<td class="week-end"><a href="javascript:void(0)">3</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">4</a></td>
									<td><a href="javascript:void(0)">5</a></td>
									<td><a href="javascript:void(0)">6</a></td>
									<td><a href="javascript:void(0)">7</a></td>
									<td><a href="javascript:void(0)">8</a></td>
									<td class="today"><a href="javascript:void(0)">9</a></td>
									<td class="week-end"><a href="javascript:void(0)">10</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">11</a></td>
									<td><a href="javascript:void(0)">12</a></td>
									<td><a href="javascript:void(0)">13</a></td>
									<td><a href="javascript:void(0)">14</a></td>
									<td><a href="javascript:void(0)">15</a></td>
									<td><a href="javascript:void(0)">16</a></td>
									<td class="week-end"><a href="javascript:void(0)">17</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">18</a></td>
									<td><a href="javascript:void(0)">19</a></td>
									<td><a href="javascript:void(0)">20</a></td>
									<td><a href="javascript:void(0)">21</a></td>
									<td><a href="javascript:void(0)">22</a></td>
									<td><a href="javascript:void(0)">23</a></td>
									<td class="week-end"><a href="javascript:void(0)">24</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">25</a></td>
									<td class="unavailable">26</td>
									<td class="unavailable">27</td>
									<td class="unavailable">28</td>
									<td><a href="javascript:void(0)">29</a></td>
									<td><a href="javascript:void(0)">30</a></td>
									<td class="week-end other-month">1</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					</div>
					</div>
					
					
					
				
				
					
					</div>
					<div class="col200pxL-right">
						
						<div id="tab-gi" class="tabs-content">
							
							<ul class="tabs js-tabs same-height">
								<li class="current"><a href="#tab-gip" title="Locales">General Information & Properties</a></li>
								<li class="current"><a href="#tab-hsp" title="Locales">Summary Planning</a></li>
								<li><a href="#tab-hsflm" title="First Line Maintenance (FLM)">First Line Maintenance (FLM)</a></li>
								<li><a href="#tab-hsslm" title="Second Line Maintenance (SLM)">Second Line Maintenance (SLM)</a></li>
								<li><a href="#tab-hslog" title="Logistics">Logistics</a></li>
							</ul>
							
							<div class="tabs-content">
								
								<div id="tab-hsp" style="height: 470px; display: block;">
								
									<div class="infos">
										<h3>PORTAL MONITORING SYSTEM</h3>
										<p>PT. BINTANG JASA ARTHA KELOLA</p>
									</div>
									
									<ul class="mini-tabs no-margin js-tabs same-height">
										<li><a href="#tab-hsp1"><img src="images/icons/flags/us.png" width="16" height="11" alt="Summary Info 01" title="Summary Info 01"></a></li>
									</ul>
									
									
									
								</div>
								
								<div id="tab-hsflm">
									First Line Maintenance (FLM)
								</div>
								<div id="tab-hsslm">
									Second Line Maintenance (SLM)
								</div>
								<div id="tab-hslog">
									Logistics
								</div>
							
							</div>
						</div>
						
						
						<div id="tab-faq" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								<p class="grey">Economics Information System (EIS) dengan ACCOUNTING MANAGEMENT SYSTEM 
								adalah suatu sistem informasi akuntansi yang berbasis jaringan-jaringan sistem pengolahan data. sistem ini 
								memungkinkan user untuk dapat melakukan management pada rekapitulasi data akuntansi nya secara berkala. </p>
								
								<p><strong>Prosedur Pengoperasian Sistem :</strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>Modul Setup Data [Data Perkiraan & Data Pofile]</dt>
									<dd>
										<p><b>Data Perkiraan</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Kode Rekening, Nama Rekening,
										Awal Debet, Awal Kredit, Posisi & Normalisasi.</p>
										<p><b>Data Profile</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Profile Perusahaan</p>
									</dd>
									
									<dt><span class="number">2</span>Modul Transaksi [Jurnal Umum, Kas Keluar & Posting Data]</dt>
									<dd>
										<p><b>Jurnal Umum</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Kas Keluar</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Kas Keluar yang mencangkup data
										Kode Rekening, Keterangan & Debet</p>
										<p><b>Posting Data</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap Posting Data yang mencangkup data
										Kode Rekening, Keterangan, Debet, Kredit & Status</p>
									</dd>
									
									<dt><span class="number">3</span>Modul Laporan [Buku Jurnal, Neraca Percobaan, Hitung SHU, Rugi Laba & Neraca]</dt>
									<dd>
										<p><b>Buku Jurnal</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Tanggal, Nomor Bukti, Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Neraca Percobaan</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca Percobaan yang mencangkup data
										Kode Rekening, Nama Rekening, Serta Debet & Kredit (Awal, Mutasi, Sisa)</p>
										<p><b>Hitung SHU</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap proses penghitungan SHU yang mencangkup data
										Sisa Hasil Usaha</p>
										<p><b>Rugi Laba</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Rugi Laba yang mencangkup data
										Kode Perkiraan, Uraian, Pengeluaran & Pendapatan</p>
										<p><b>Neraca</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca yang mencangkup data
										Neraca Aktiva & Neraca Pasiva</p>
									</dd>
									
									<dt><span class="number">4</span>Modul Backup Data & Restore Data</dt>
									<dd>
										<p><b>Backup Data</b><br>Pada menu ini, user dapat melakukan Backup data dengan cara menekan tombol "Backup Database" kemudian akan muncul form
										untuk saving direktori penyimpanan file nya, kemudian klik save file dan tekan tombol Ok.</p>
										<p><b>Restore Data</b><br>Pada menu ini, user dapat melakukan Restore data dengan cara menekan tombol "Browse" kemudian akan muncul form
										untuk memilih file pada direktori penyimpanan file nya, kemudian klik Open dan tekan tombol Restore Data.</p>
									</dd>
									
									<dt><span class="number">5</span>Modul Data User [List, Add User, Settings & Logout]</dt>
									<dd>
										<p><b>List User</b><br>Menu ini berisi daftar user / pengguna yang mempunyai hak akses didalam sistem.</p>
										<p><b>Add User</b><br>Menu ini berfungsi untuk menambahkan data user / pengguna untuk mendapatkan dan mempunyai hak akses terhadap sistem</p>
										<p><b>Settings</b><br>Menu ini berfungsi untuk melakukan pengaturan pada data user / pengguna</p>
										<p><b>Logout</b><br>User / pengguna dapat melakukan Logout terhadap sistem dengan menekan tombol "Logout"</p>
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						
						<div id="tab-op" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								<p class="grey">Economics Information System (EIS) dengan ACCOUNTING MANAGEMENT SYSTEM 
								adalah suatu sistem informasi akuntansi yang berbasis jaringan-jaringan sistem pengolahan data. sistem ini 
								memungkinkan user untuk dapat melakukan management pada rekapitulasi data akuntansi nya secara berkala. </p>
								
								<p><strong>Prosedur Pengoperasian Sistem :</strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>Modul Setup Data [Data Perkiraan & Data Pofile]</dt>
									<dd>
										<p><b>Data Perkiraan</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Kode Rekening, Nama Rekening,
										Awal Debet, Awal Kredit, Posisi & Normalisasi.</p>
										<p><b>Data Profile</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Profile Perusahaan</p>
									</dd>
									
									<dt><span class="number">2</span>Modul Transaksi [Jurnal Umum, Kas Keluar & Posting Data]</dt>
									<dd>
										<p><b>Jurnal Umum</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Kas Keluar</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Kas Keluar yang mencangkup data
										Kode Rekening, Keterangan & Debet</p>
										<p><b>Posting Data</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap Posting Data yang mencangkup data
										Kode Rekening, Keterangan, Debet, Kredit & Status</p>
									</dd>
									
									<dt><span class="number">3</span>Modul Laporan [Buku Jurnal, Neraca Percobaan, Hitung SHU, Rugi Laba & Neraca]</dt>
									<dd>
										<p><b>Buku Jurnal</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Tanggal, Nomor Bukti, Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Neraca Percobaan</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca Percobaan yang mencangkup data
										Kode Rekening, Nama Rekening, Serta Debet & Kredit (Awal, Mutasi, Sisa)</p>
										<p><b>Hitung SHU</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap proses penghitungan SHU yang mencangkup data
										Sisa Hasil Usaha</p>
										<p><b>Rugi Laba</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Rugi Laba yang mencangkup data
										Kode Perkiraan, Uraian, Pengeluaran & Pendapatan</p>
										<p><b>Neraca</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca yang mencangkup data
										Neraca Aktiva & Neraca Pasiva</p>
									</dd>
									
									<dt><span class="number">4</span>Modul Backup Data & Restore Data</dt>
									<dd>
										<p><b>Backup Data</b><br>Pada menu ini, user dapat melakukan Backup data dengan cara menekan tombol "Backup Database" kemudian akan muncul form
										untuk saving direktori penyimpanan file nya, kemudian klik save file dan tekan tombol Ok.</p>
										<p><b>Restore Data</b><br>Pada menu ini, user dapat melakukan Restore data dengan cara menekan tombol "Browse" kemudian akan muncul form
										untuk memilih file pada direktori penyimpanan file nya, kemudian klik Open dan tekan tombol Restore Data.</p>
									</dd>
									
									<dt><span class="number">5</span>Modul Data User [List, Add User, Settings & Logout]</dt>
									<dd>
										<p><b>List User</b><br>Menu ini berisi daftar user / pengguna yang mempunyai hak akses didalam sistem.</p>
										<p><b>Add User</b><br>Menu ini berfungsi untuk menambahkan data user / pengguna untuk mendapatkan dan mempunyai hak akses terhadap sistem</p>
										<p><b>Settings</b><br>Menu ini berfungsi untuk melakukan pengaturan pada data user / pengguna</p>
										<p><b>Logout</b><br>User / pengguna dapat melakukan Logout terhadap sistem dengan menekan tombol "Logout"</p>
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						<div id="tab-sl" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								<p class="grey">Economics Information System (EIS) dengan ACCOUNTING MANAGEMENT SYSTEM 
								adalah suatu sistem informasi akuntansi yang berbasis jaringan-jaringan sistem pengolahan data. sistem ini 
								memungkinkan user untuk dapat melakukan management pada rekapitulasi data akuntansi nya secara berkala. </p>
								
								<p><strong>Prosedur Pengoperasian Sistem :</strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>Modul Setup Data [Data Perkiraan & Data Pofile]</dt>
									<dd>
										<p><b>Data Perkiraan</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Kode Rekening, Nama Rekening,
										Awal Debet, Awal Kredit, Posisi & Normalisasi.</p>
										<p><b>Data Profile</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Profile Perusahaan</p>
									</dd>
									
									<dt><span class="number">2</span>Modul Transaksi [Jurnal Umum, Kas Keluar & Posting Data]</dt>
									<dd>
										<p><b>Jurnal Umum</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Kas Keluar</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Kas Keluar yang mencangkup data
										Kode Rekening, Keterangan & Debet</p>
										<p><b>Posting Data</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap Posting Data yang mencangkup data
										Kode Rekening, Keterangan, Debet, Kredit & Status</p>
									</dd>
									
									<dt><span class="number">3</span>Modul Laporan [Buku Jurnal, Neraca Percobaan, Hitung SHU, Rugi Laba & Neraca]</dt>
									<dd>
										<p><b>Buku Jurnal</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Tanggal, Nomor Bukti, Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Neraca Percobaan</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca Percobaan yang mencangkup data
										Kode Rekening, Nama Rekening, Serta Debet & Kredit (Awal, Mutasi, Sisa)</p>
										<p><b>Hitung SHU</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap proses penghitungan SHU yang mencangkup data
										Sisa Hasil Usaha</p>
										<p><b>Rugi Laba</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Rugi Laba yang mencangkup data
										Kode Perkiraan, Uraian, Pengeluaran & Pendapatan</p>
										<p><b>Neraca</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca yang mencangkup data
										Neraca Aktiva & Neraca Pasiva</p>
									</dd>
									
									<dt><span class="number">4</span>Modul Backup Data & Restore Data</dt>
									<dd>
										<p><b>Backup Data</b><br>Pada menu ini, user dapat melakukan Backup data dengan cara menekan tombol "Backup Database" kemudian akan muncul form
										untuk saving direktori penyimpanan file nya, kemudian klik save file dan tekan tombol Ok.</p>
										<p><b>Restore Data</b><br>Pada menu ini, user dapat melakukan Restore data dengan cara menekan tombol "Browse" kemudian akan muncul form
										untuk memilih file pada direktori penyimpanan file nya, kemudian klik Open dan tekan tombol Restore Data.</p>
									</dd>
									
									<dt><span class="number">5</span>Modul Data User [List, Add User, Settings & Logout]</dt>
									<dd>
										<p><b>List User</b><br>Menu ini berisi daftar user / pengguna yang mempunyai hak akses didalam sistem.</p>
										<p><b>Add User</b><br>Menu ini berfungsi untuk menambahkan data user / pengguna untuk mendapatkan dan mempunyai hak akses terhadap sistem</p>
										<p><b>Settings</b><br>Menu ini berfungsi untuk melakukan pengaturan pada data user / pengguna</p>
										<p><b>Logout</b><br>User / pengguna dapat melakukan Logout terhadap sistem dengan menekan tombol "Logout"</p>
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						<div id="tab-hds" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								<p class="grey">Economics Information System (EIS) dengan ACCOUNTING MANAGEMENT SYSTEM 
								adalah suatu sistem informasi akuntansi yang berbasis jaringan-jaringan sistem pengolahan data. sistem ini 
								memungkinkan user untuk dapat melakukan management pada rekapitulasi data akuntansi nya secara berkala. </p>
								
								<p><strong>Prosedur Pengoperasian Sistem :</strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>Modul Setup Data [Data Perkiraan & Data Pofile]</dt>
									<dd>
										<p><b>Data Perkiraan</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Kode Rekening, Nama Rekening,
										Awal Debet, Awal Kredit, Posisi & Normalisasi.</p>
										<p><b>Data Profile</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Profile Perusahaan</p>
									</dd>
									
									<dt><span class="number">2</span>Modul Transaksi [Jurnal Umum, Kas Keluar & Posting Data]</dt>
									<dd>
										<p><b>Jurnal Umum</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Kas Keluar</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Kas Keluar yang mencangkup data
										Kode Rekening, Keterangan & Debet</p>
										<p><b>Posting Data</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap Posting Data yang mencangkup data
										Kode Rekening, Keterangan, Debet, Kredit & Status</p>
									</dd>
									
									<dt><span class="number">3</span>Modul Laporan [Buku Jurnal, Neraca Percobaan, Hitung SHU, Rugi Laba & Neraca]</dt>
									<dd>
										<p><b>Buku Jurnal</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Tanggal, Nomor Bukti, Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Neraca Percobaan</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca Percobaan yang mencangkup data
										Kode Rekening, Nama Rekening, Serta Debet & Kredit (Awal, Mutasi, Sisa)</p>
										<p><b>Hitung SHU</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap proses penghitungan SHU yang mencangkup data
										Sisa Hasil Usaha</p>
										<p><b>Rugi Laba</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Rugi Laba yang mencangkup data
										Kode Perkiraan, Uraian, Pengeluaran & Pendapatan</p>
										<p><b>Neraca</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca yang mencangkup data
										Neraca Aktiva & Neraca Pasiva</p>
									</dd>
									
									<dt><span class="number">4</span>Modul Backup Data & Restore Data</dt>
									<dd>
										<p><b>Backup Data</b><br>Pada menu ini, user dapat melakukan Backup data dengan cara menekan tombol "Backup Database" kemudian akan muncul form
										untuk saving direktori penyimpanan file nya, kemudian klik save file dan tekan tombol Ok.</p>
										<p><b>Restore Data</b><br>Pada menu ini, user dapat melakukan Restore data dengan cara menekan tombol "Browse" kemudian akan muncul form
										untuk memilih file pada direktori penyimpanan file nya, kemudian klik Open dan tekan tombol Restore Data.</p>
									</dd>
									
									<dt><span class="number">5</span>Modul Data User [List, Add User, Settings & Logout]</dt>
									<dd>
										<p><b>List User</b><br>Menu ini berisi daftar user / pengguna yang mempunyai hak akses didalam sistem.</p>
										<p><b>Add User</b><br>Menu ini berfungsi untuk menambahkan data user / pengguna untuk mendapatkan dan mempunyai hak akses terhadap sistem</p>
										<p><b>Settings</b><br>Menu ini berfungsi untuk melakukan pengaturan pada data user / pengguna</p>
										<p><b>Logout</b><br>User / pengguna dapat melakukan Logout terhadap sistem dengan menekan tombol "Logout"</p>
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						<div id="tab-dla" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								<p class="grey">Economics Information System (EIS) dengan ACCOUNTING MANAGEMENT SYSTEM 
								adalah suatu sistem informasi akuntansi yang berbasis jaringan-jaringan sistem pengolahan data. sistem ini 
								memungkinkan user untuk dapat melakukan management pada rekapitulasi data akuntansi nya secara berkala. </p>
								
								<p><strong>Prosedur Pengoperasian Sistem :</strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>Modul Setup Data [Data Perkiraan & Data Pofile]</dt>
									<dd>
										<p><b>Data Perkiraan</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Kode Rekening, Nama Rekening,
										Awal Debet, Awal Kredit, Posisi & Normalisasi.</p>
										<p><b>Data Profile</b><br>Menu ini berfungsi untuk melakukan pengaturan terhadap data Profile Perusahaan</p>
									</dd>
									
									<dt><span class="number">2</span>Modul Transaksi [Jurnal Umum, Kas Keluar & Posting Data]</dt>
									<dd>
										<p><b>Jurnal Umum</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Kas Keluar</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Kas Keluar yang mencangkup data
										Kode Rekening, Keterangan & Debet</p>
										<p><b>Posting Data</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap Posting Data yang mencangkup data
										Kode Rekening, Keterangan, Debet, Kredit & Status</p>
									</dd>
									
									<dt><span class="number">3</span>Modul Laporan [Buku Jurnal, Neraca Percobaan, Hitung SHU, Rugi Laba & Neraca]</dt>
									<dd>
										<p><b>Buku Jurnal</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Jurnal umum yang mencangkup data
										Tanggal, Nomor Bukti, Kode Rekening, Keterangan, Debet & Kredit</p>
										<p><b>Neraca Percobaan</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca Percobaan yang mencangkup data
										Kode Rekening, Nama Rekening, Serta Debet & Kredit (Awal, Mutasi, Sisa)</p>
										<p><b>Hitung SHU</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap proses penghitungan SHU yang mencangkup data
										Sisa Hasil Usaha</p>
										<p><b>Rugi Laba</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Rugi Laba yang mencangkup data
										Kode Perkiraan, Uraian, Pengeluaran & Pendapatan</p>
										<p><b>Neraca</b><br>Menu ini berfungsi untuk melakukan pengoperasian terhadap data Neraca yang mencangkup data
										Neraca Aktiva & Neraca Pasiva</p>
									</dd>
									
									<dt><span class="number">4</span>Modul Backup Data & Restore Data</dt>
									<dd>
										<p><b>Backup Data</b><br>Pada menu ini, user dapat melakukan Backup data dengan cara menekan tombol "Backup Database" kemudian akan muncul form
										untuk saving direktori penyimpanan file nya, kemudian klik save file dan tekan tombol Ok.</p>
										<p><b>Restore Data</b><br>Pada menu ini, user dapat melakukan Restore data dengan cara menekan tombol "Browse" kemudian akan muncul form
										untuk memilih file pada direktori penyimpanan file nya, kemudian klik Open dan tekan tombol Restore Data.</p>
									</dd>
									
									<dt><span class="number">5</span>Modul Data User [List, Add User, Settings & Logout]</dt>
									<dd>
										<p><b>List User</b><br>Menu ini berisi daftar user / pengguna yang mempunyai hak akses didalam sistem.</p>
										<p><b>Add User</b><br>Menu ini berfungsi untuk menambahkan data user / pengguna untuk mendapatkan dan mempunyai hak akses terhadap sistem</p>
										<p><b>Settings</b><br>Menu ini berfungsi untuk melakukan pengaturan pada data user / pengguna</p>
										<p><b>Logout</b><br>User / pengguna dapat melakukan Logout terhadap sistem dengan menekan tombol "Logout"</p>
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						
					</div>
					
				<!-- THIS PLACE FOR MINI CALENDAR-->
				
				
				
				</div>
				
			</form></div>
		</section>
		
	
	
		<!--<section class="grid_4">
			<div class="block-border"><div class="block-content">
				<h1>List calendar 123</h1>
				
				<div class="block-controls">
					<ul class="controls-buttons">
						<li>
							<label for="show-empty">Show empty days</label>
							<input type="checkbox" name="show-empty" id="show-empty" value="1" class="mini-switch" checked="checked">
						</li>
					</ul>
				</div>
				
				<ul class="message no-margin">
					<li>12 events found</li>
				</ul>
				
				<div class="no-margin">
					<table cellspacing="0" class="list-calendar">
						<tbody>
							<tr class="empty">
								<th scope="row">01</th>
								<td>
									<ul class="mini-menu">
										<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
									</ul>
									No events
								</td>
							</tr>
							<tr>
								<th scope="row">02</th>
								<td>
									<ul class="mini-menu">
										<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
									</ul>
									<ul class="events-dots-list">
										<li><a href="javascript:void(0)"><span></span> Lena's birthday</a></li>
										<li><a href="javascript:void(0)"><span></span> Replace server hard drive</a></li>
										<li><a href="javascript:void(0)"><span></span> Max's birthday</a></li>
									</ul>
								</td>
							</tr>
							<tr class="empty">
								<th scope="row">03</th>
								<td>
									<ul class="mini-menu">
										<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
									</ul>
									No events
								</td>
							</tr>
							<tr class="empty">
								<th scope="row">04</th>
								<td>
									<ul class="mini-menu">
										<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
									</ul>
									No events
								</td>
							</tr>
							<tr>
								<th scope="row">05</th>
								<td>
									<ul class="mini-menu">
										<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
									</ul>
									<ul class="events">
										<li><a href="javascript:void(0)"><b>9:00</b> Meeting</a></li>
										<li><a href="javascript:void(0)"><b>11:00</b> Meeting with D.H.</a></li>
										<li><a href="javascript:void(0)"><b>14:00</b> Meeting</a></li>
									</ul>
									<div class="more-events">
										2 more events
										<ul>
											<li><a href="javascript:void(0)"><b>17:00</b> Soccer</a></li>
											<li><a href="javascript:void(0)"><b>21:00</b> Diner with Jane</a></li>
										</ul>
									</div>
								</td>
							</tr>
							<tr class="empty">
								<th scope="row">06</th>
								<td>
									<ul class="mini-menu">
										<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
									</ul>
									No events
								</td>
							</tr>
							<tr>
								<th scope="row">07</th>
								<td>
									<ul class="mini-menu">
										<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
									</ul>
									<ul class="events">
										<li><a href="javascript:void(0)"><b>9:00</b> Meeting</a></li>
									</ul>
								</td>
							</tr>
							<tr>
								<th scope="row">08</th>
								<td>
									<ul class="mini-menu">
										<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
									</ul>
									<ul class="events-dots-list">
										<li class="red"><span></span> Tax payment limit date</li>
										<li><a href="javascript:void(0)"><span></span> Check server hard drive logs</a></li>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			
			</div></div>
		</section>
		-->
		
		<div class="clear"></div>
		
	</article>
@endsection