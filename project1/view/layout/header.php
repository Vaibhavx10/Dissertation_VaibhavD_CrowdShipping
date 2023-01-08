<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LML Crowd Shipping</title>
    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">
    <!-- Styles -->
    <link href="<?php echo $asset_url; ?>assets/css/lib/chartist/chartist.min.css" rel="stylesheet">
    <link href="<?php echo $asset_url; ?>assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $asset_url; ?>assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="<?php echo $asset_url; ?>assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="<?php echo $asset_url; ?>assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="<?php echo $asset_url; ?>assets/css/lib/weather-icons.css" rel="stylesheet" />
    <link href="<?php echo $asset_url; ?>assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="<?php echo $asset_url; ?>assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $asset_url; ?>assets/css/lib/unix.css" rel="stylesheet">
    <link href="<?php echo $asset_url; ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo $asset_url; ?>assets/css/lib/data-table/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $asset_url; ?>assets/css/lib/data-table/buttons.bootstrap.min.css" rel="stylesheet" />
    <!--DataTable -->

    <!--End  -->

</head>

<body>

    <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <?php include('menu.php'); ?>
            </div>
        </div>
    </div>
    <!-- /# sidebar -->
    <div class="header">
        <div class="pull-left">
            <div class="logo"><a href="<?php echo $base_url; ?>">
                    <span>LML Crowd Shipping</span>
                </a></div>
            <div class="hamburger sidebar-toggle">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>
        <div class="pull-right p-r-15">
            <ul>
                <li class="header-icon dib"><a href="#search"><i class="ti-search"></i></a></li>

                <?php $sess_user = $_SESSION['user']; ?>
                <li class="header-icon dib"><img class="avatar-img" src="<?php echo $asset_url; ?>assets/images/avatar/1.jpg" alt="" /> <span class="user-avatar"> <?php echo $sess_user['username'];?> <i class="ti-angle-down f-s-10"></i></span>
                    <div class="drop-down dropdown-profile">
                        <div class="dropdown-content-body">
                            <ul>
                                 <?php if($sess_user['type'] == 'driver' || $sess_user['type'] == 'user' ){?>
                                    <li><a href="<?php echo $base_url;?>profile"><i class="ti-user"></i> <span>Profile</span></a></li>
                                 <?php } ?>
                                 <li><a href="<?php echo $base_url;?>logout"><i class="ti-power-off"></i> <span>Logout</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>