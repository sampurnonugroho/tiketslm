@extends('layouts.maps')
@extends('layouts.menu_left')

@section('content')
	<div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Add New {{$title}}</div>
                <hr>
                <form action="<?php base_url('branch/add') ?>" autocomplete="off" method="post" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Bank</label>
                        <div class="col-sm-9">
                            <select class="form-control single-select">
                                <option>Mandiri</option>
                                <option>BCA</option>
                                <option>BNI</option>
                                <option>BRI</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Branch</label>
                        <div class="col-sm-9">
                            <select class="form-control single-select">
                                <option>Pasar Minggu</option>
                                <option>Lenteng Agung</option>
                                <option>Tanjung Barat</option>
                                <option>Pancoran</option>
                                <option>Condet</option>
                                <option>Kalimalang</option>
                                <option>Cilandak</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="branch_name" class="col-sm-3 col-form-label">Location</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="location_input" id="location_input" placeholder="Enter Name">
                        </div>
                    </div>

                    <select id="zoom" class="pull-right" hidden>
                        <option value="">Pilih</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                    </select>

                    <select id="location" class="pull-right" hidden>
                        <option value="">Pilih</option>
                        <option value="106.84513,-6.21462">Jakarta</option>
                    </select>
                    <div id="marker-map" class="gmaps"></div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-white btn-round px-5 pull-right"><i class="icon-lock"></i> Save</button>
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