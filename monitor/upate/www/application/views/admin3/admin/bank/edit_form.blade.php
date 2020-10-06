@extends('layouts.table')
@extends('layouts.menu_left')

@section('content')
	<div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Add New {{$title}}</div>
                <hr>
                <form action="<?php base_url('bank/edit') ?>" autocomplete="off" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $bank->id?>" />
                    <div class="form-group row">
                        <label for="bank_name" class="col-sm-2 col-form-label">Bank Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-rounded" name="bank_name" id="bank_name" placeholder="Enter Name" value="<?php echo $bank->bank_name ?>">
                            <label class="error" for="bank_name"><?php echo form_error('bank_name') ?></label>
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