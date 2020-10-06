@extends('layouts.table')
@extends('layouts.menu_left')

@section('content')
	<div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Add New {{$title}}</div>
                <hr>
                <form action="<?php base_url('branch/edit') ?>" autocomplete="off" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $branch->id?>" />

                    <div class="form-group row">
                        <label for="branch_name" class="col-sm-2 col-form-label">Branch Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-rounded" name="branch_name" id="branch_name" placeholder="Enter Branch Name" value="<?php echo $branch->branch_name ?>">
                            <label class="error" for="branch_name"><?php echo form_error('branch_name') ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label> 
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-white btn-round px-5 pull-right"><i class="icon-lock"></i> Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
	<!-- End container-fluid-->
@endsection

<script type="text/javascript">
    
</script>