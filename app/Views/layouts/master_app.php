<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://eduadmin-template.multipurposethemes.com/bs5/images/favicon.ico">
    <title><?= $title; ?> | Schedule Project</title>
    <!-- Vendors Style-->
    <link rel="stylesheet" href="assets/css/vendors_css.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.3/css/all.css">
    <!-- Style-->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/skin_color.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

    <div class="wrapper">
        <header class="main-header">
            <div class="d-flex align-items-center logo-box justify-content-start">
                <a href="#" class="waves-effect waves-light nav-link d-none d-md-inline-block mx-10 push-btn bg-transparent" data-toggle="push-menu" role="button">
                    <span class="icon-Align-left"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
                </a>
                <!-- Logo -->
                <a href="index-2.html" class="logo">
                    <!-- logo-->
                    <div class="logo-lg">
                        <span class="light-logo"><img src="assets/images/logo-light-text.gif" style="margin-left: -15px;" alt="logo"></span>
                        <span class="dark-logo"><img src="assets/images/logo-dark-text.gif" style="margin-left: -15px;" alt="logo"></span>
                    </div>
                </a>
            </div>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <div class="app-menu">
                </div>

                <div class="navbar-custom-menu r-side">
                    <ul class="nav navbar-nav">
                        <li class="btn-group nav-item d-lg-inline-flex d-none">
                            <a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link full-screen" title="Full Screen">
                                <i class="icon-Expand-arrows"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                        </li>
                        <li class="btn-group d-lg-inline-flex d-none">
                            <div class="app-menu">
                                <div class="search-bx mx-5">
                                    <form>
                                        <div class="input-group">
                                            <input type="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                                            <div class="input-group-append">
                                                <button class="btn" type="submit" id="button-addon3"><i class="ti-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <!-- Notifications -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" title="Notifications">
                                <i class="icon-Notifications"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <ul class="dropdown-menu animated bounceIn">

                                <li class="header">
                                    <div class="p-20">
                                        <div class="flexbox">
                                            <div>
                                                <h4 class="mb-0 mt-0">Notifications</h4>
                                            </div>
                                            <div>
                                                <a href="#" class="text-danger">Clear All</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu sm-scrol">
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-info"></i> Curabitur id eros quis nunc suscipit blandit.
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all</a>
                                </li>
                            </ul>
                        </li>

                        <!-- User Account-->
                        <li class="dropdown user user-menu">
                            <a href="#" class="waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" title="User">
                                <i class="icon-User"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <ul class="dropdown-menu animated flipInX">
                                <li class="user-body">
                                    <a class="dropdown-item" href="Profil"><i class="ti-user text-muted me-2"></i> Profile</a>
                                    <a class="dropdown-item" href="<?= base_url('logout')?>"><i class="ti-lock text-muted me-2"></i> Logout</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Control Sidebar Toggle Button -->

                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="#" id="themeSwitch" class="waves-effect waves-light" onclick="switchTheme()">
                                <i class="icon-Moon"><span class="path1"></span><span class="path2"></span></i>
                                <i class="icon-Sun" style="display: none; color: yellow;"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar-->
            <section class="sidebar position-relative">
                <div class="multinav">
                    <div class="multinav-scroll" style="height: 100%;">
                        <!-- sidebar menu-->
                        <ul class="sidebar-menu" data-widget="tree">
                            <li class="header">Dashboard</li>
                            <li>
                                <a href="Dashboard">
                                    <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <?php if(session()->get('level') == 'Admin') : ?>
                            <li class="header">Master Data</li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="icon-Layout-grid"><span class="path1"></span><span class="path2"></span></i>
                                    <span>Data Pengguna</span>
                                    <span class="pull-right-container">
                                        <i class="fad fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="Admin"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Admin</a></li>
                                    <li><a href="Vendor"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Vendor</a></li>
                                    <li><a href="Pegawai"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Pegawai</a></li>
                                </ul>
                            </li>
                            <?php endif; ?>
                            <li class="header">Daftar Pekerjaan</li>
                            <?php if(session()->get('level') == 'Admin') : ?>
                            <li>
                                <a href="Project">
                                    <i class="icon-File"><span class="path1"></span><span class="path2"></span></i>
                                    <span>Project</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(session()->get('level') != 'Pegawai') : ?>
                            <li>
                                <a href="Projectvendor">
                                    <i class="icon-Write"><span class="path1"></span><span class="path2"></span></i>
                                    <span>Project <?= session()->get('level') == 'Vendor' ? 'Saya' : 'Vendor' ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(session()->get('level') != 'Vendor') : ?>
                            <li>
                                <a href="Projectpegawai">
                                    <i class="icon-Chat-check"><span class="path1"></span><span class="path2"></span></i>
                                    <span>Project <?= session()->get('level') == 'Pegawai' ? 'Saya' : 'Pegawai' ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </section>
            <div class="sidebar-footer">
                <a href="javascript:void(0)" class="link" data-bs-toggle="tooltip" title="Settings"><span class="icon-Settings-2"></span></a>
                <a href="mailbox.html" class="link" data-bs-toggle="tooltip" title="Email"><span class="icon-Mail"></span></a>
                <a href="javascript:void(0)" class="link" data-bs-toggle="tooltip" title="Logout"><span class="icon-Lock-overturning"><span class="path1"></span><span class="path2"></span></span></a>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-full">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="me-auto">
                            <?php if ($title == 'Dashboard') : ?>
                                <h3 class="page-title text-capitalize"><?= $title ?></h3>
                            <?php else : ?>
                                <h3 class="page-title text-capitalize">Data <?= $title ?></h3>
                            <?php endif; ?>
                            <div class="d-inline-block align-items-center">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#"><i class="fad fa-home"></i></a></li>
                                        <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                                        <?php if ($title != 'Dashboard') : ?>
                                            <li class="breadcrumb-item active text-capitalize" aria-current="page">Data <?= $title ?></li>
                                        <?php endif; ?>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Main content -->

                <?= $this->renderSection('content') ?>

                <!-- /.content -->

            </div>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            &copy; <?= date('Y') ?> <a href="#"> Aisyah Coding || Group Project</a>. All Rights Reserved.
        </footer>

    </div>
    <!-- ./wrapper -->



    <!-- Page Content overlay -->


    <!-- Vendor JS -->
    <?= $this->renderSection('script') ?>
    <script src="assets/vendor_components/datatable/datatables.min.js"></script>
    <script src="assets/js/vendors.min.js"></script>
    <!-- EduAdmin App -->
    <script src="assets/js/template.js"></script>
    <script src="assets/js/theme_change.js"></script>
    <script src="assets/js/pages/validation.js"></script>
    <script src="assets/js/pages/form-validation.js"></script>
</body>

</html>