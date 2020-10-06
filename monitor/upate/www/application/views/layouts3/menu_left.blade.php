@section('left_menus')
    <div class="brand-logo">
        <a href="index.html">
            <img src="<?=base_url()?>assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
            <h5 class="logo-text">Dashtreme Admin</h5>
        </a>
    </div>
    <div class="user-details">
        <div class="media align-items-center user-pointer collapsed" data-toggle="collapse" data-target="#user-dropdown">
            <div class="avatar"><img class="mr-3 side-user-img" src="<?=base_url()?>assets/images/avatars/avatar-13.png" alt="user avatar"></div>
            <div class="media-body">
                <h6 class="side-user-name">Mark Jhonsan</h6>
            </div>
        </div>
        <div id="user-dropdown" class="collapse">
            <ul class="user-setting-menu">
                <li><a href="javaScript:void();"><i class="icon-user"></i> My Profile</a></li>
                <li><a href="javaScript:void();"><i class="icon-settings"></i> Setting</a></li>
                <li><a href="javaScript:void();"><i class="icon-power"></i> Logout</a></li>
            </ul>
        </div>
    </div>
    <ul class="sidebar-menu do-nicescrol">
        <li class="sidebar-header">MAIN NAVIGATION</li>
        <li>
            <a href="javaScript:void();" class="waves-effect">
                <i class="zmdi zmdi-view-dashboard"></i> <span>Dashboard</span><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="index.html"><i class="zmdi zmdi-long-arrow-right"></i> Ecommerce</a></li>
                <li><a href="index2.html"><i class="zmdi zmdi-long-arrow-right"></i> Property Listings</a></li>
            </ul>
        </li>
        <li>
            <a href="javaScript:void();" class="waves-effect">
                <i class="zmdi zmdi-layers"></i>
                <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="sidebar-submenu">
                <li><a href="<?=base_url()?>bank"><i class="zmdi zmdi-long-arrow-right"></i> Bank</a></li>
                <li><a href="<?=base_url()?>branch"><i class="zmdi zmdi-long-arrow-right"></i> Branch</a></li>
                <li><a href="<?=base_url()?>atm"><i class="zmdi zmdi-long-arrow-right"></i> Atm</a></li>
                <li><a href="<?=base_url()?>maps"><i class="zmdi zmdi-long-arrow-right"></i> Maps</a></li>
            </ul>
        </li>
    </ul>
@endsection