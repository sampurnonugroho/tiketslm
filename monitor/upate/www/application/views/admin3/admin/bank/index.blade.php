@extends('layouts.table')
@extends('layouts.menu_left')

@section('content')
	<div class="container-fluid">
		<!-- Breadcrumb-->
		<div class="row pt-2 pb-2">
			<div class="col-sm-9">
				<h4 class="page-title">Data {{$title}}</h4>
				<!-- <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="javaScript:void();">Dashtreme</a></li>
					<li class="breadcrumb-item"><a href="javaScript:void();">Tables</a></li>
					<li class="breadcrumb-item active" aria-current="page">Data Tables</li>
				</ol> -->
			</div>
			<div class="col-sm-3">
				<div class="btn-group float-sm-right">
					<button type="button" onclick="window.location.href='<?=base_url()?>bank/add'" class="btn btn-light waves-effect waves-light"><i class="fa fa-plus mr-1"></i> Add Data</button>
				</div>
			</div>
		</div>
		<!-- End Breadcrumb-->
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="default-datatable" class="table table-bordered">
								<thead>
									<tr>
										<th>No</th>
										<th>Bank Name</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$no = 0;
										foreach($bank as $r): 
										$no++;
									?>
									<tr>
										<td><?=$no?></td>
										<td><?=$r->bank_name?></td>
										<td width="200">
											<a href="<?php echo site_url('bank/edit/'.$r->id) ?>"
											 class="btn btn-light waves-effect waves-light"><i class="fa fa-edit"></i> Edit</a>
											<a onclick="deleteConfirm('<?php echo site_url('bank/delete/'.$r->id) ?>')"
											 href="#!" class="btn btn-light waves-effect waves-light"><i class="fa fa-trash"></i> Hapus</a>
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<th>No</th>
										<th>Branch Name</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div><!-- End Row-->

	</div>
	<!-- End container-fluid-->
@endsection