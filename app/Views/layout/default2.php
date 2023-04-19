<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="author" content="PIXINVENT">
  <title><?= $title ?></title>
  <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
  <!-- <link rel="shortcut icon" type="image/x-icon" href="https://pixinvent.com/modern-admin-clean-bootstrap-4-dashboard-html-template/app-assets/images/ico/favicon.ico"> -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">

  <!-- css template -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/vendors/css/vendors.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/vendors/css/forms/selects/select2.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/vendors/css/fonts/simple-line-icons/style.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/css/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('vendors/pnotify/pnotify.custom.min.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/css/bootstrap-extended.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/css/colors.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/css/components.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/css/core/menu/menu-types/vertical-menu-modern.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/css/core/menu/menu-types/horizontal-menu.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/style.css') ?>">

  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url() ?>/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url() ?>/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url() ?>/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url() ?>/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url() ?>/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url() ?>/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url() ?>/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url() ?>/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url() ?>/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url() ?>/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url() ?>/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url() ?>/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url() ?>/favicon/favicon-16x16.png">
  <?php $this->renderSection('plugin_css') ?>
  <?php $this->renderSection('custom_css') ?>
  <script>
    var siteUrl = '<?php echo base_url(); ?>'
  </script>
</head>
<?php ?>

<!-- BEGIN: Body-->
<body class="horizontal-layout horizontal-menu 2-columns  " data-open="hover" data-menu="horizontal-menu" data-col="2-columns">

<!-- BEGIN: Header-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow navbar-static-top navbar-light navbar-brand-center">
  <div class="navbar-wrapper">
	<div class="navbar-header">
	  <ul class="nav navbar-nav flex-row">
		<li class="nav-item mobile-menu d-md-none mr-auto">
			<a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
				<i class="ft-menu font-large-1"></i>
			</a>
		</li>
		<li class="nav-item">
			<a class="navbar-brand mt-1 p-0" href="index-2.html">
				<img class="brand-logo" style="width: 70px!important" alt="modern admin logo" src="<?= base_url('images/logo/logo.png') ?>">
				<h3 class="brand-text d-none">PT Tekpak Indonesia</h3>
			</a>
		</li>
		<li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
	  </ul>
	</div>
	<div class="navbar-container content">
	  <div class="collapse navbar-collapse" id="navbar-mobile">
		<ul class="nav navbar-nav mr-auto float-left">
			<li class="nav-item d-none d-md-block">
				<a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
					<i class="ft-menu"></i>
				</a>
			</li>
		</ul>
		<ul class="nav navbar-nav float-right">
		  <?php if(logged_in()) : ?>
		  <li class="d-none dropdown dropdown-notification nav-item">
			<a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-bell"></i><span class="badge badge-pill badge-danger badge-up badge-glow">5</span></a>
			<ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
			  <li class="dropdown-menu-header">
				<h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span></h6><span class="notification-tag badge badge-danger float-right m-0">5 New</span>
			  </li>
			  <li class="scrollable-container media-list w-100"><a href="javascript:void(0)">
				  <div class="media">
					<div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-cyan mr-0"></i></div>
					<div class="media-body">
					  <h6 class="media-heading">You have new order!</h6>
					  <p class="notification-text font-small-3 text-muted">Lorem ipsum dolor sit amet, consectetuer elit.</p><small>
						<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">30 minutes ago</time></small>
					</div>
				  </div></a><a href="javascript:void(0)">
				  <div class="media">
					<div class="media-left align-self-center"><i class="ft-download-cloud icon-bg-circle bg-red bg-darken-1 mr-0"></i></div>
					<div class="media-body">
					  <h6 class="media-heading red darken-1">99% Server load</h6>
					  <p class="notification-text font-small-3 text-muted">Aliquam tincidunt mauris eu risus.</p><small>
						<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Five hour ago</time></small>
					</div>
				  </div></a><a href="javascript:void(0)">
				  <div class="media">
					<div class="media-left align-self-center"><i class="ft-alert-triangle icon-bg-circle bg-yellow bg-darken-3 mr-0"></i></div>
					<div class="media-body">
					  <h6 class="media-heading yellow darken-3">Warning notifixation</h6>
					  <p class="notification-text font-small-3 text-muted">Vestibulum auctor dapibus neque.</p><small>
						<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Today</time></small>
					</div>
				  </div></a><a href="javascript:void(0)">
				  <div class="media">
					<div class="media-left align-self-center"><i class="ft-check-circle icon-bg-circle bg-cyan mr-0"></i></div>
					<div class="media-body">
					  <h6 class="media-heading">Complete the task</h6><small>
						<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last week</time></small>
					</div>
				  </div></a><a href="javascript:void(0)">
				  <div class="media">
					<div class="media-left align-self-center"><i class="ft-file icon-bg-circle bg-teal mr-0"></i></div>
					<div class="media-body">
					  <h6 class="media-heading">Generate monthly report</h6><small>
						<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last month</time></small>
					</div>
				  </div></a></li>
			  <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all notifications</a></li>
			</ul>
		  </li>
		  <li class="dropdown dropdown-user nav-item">
			<a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
				<span class="mr-1 user-name text-bold-700"><?= $user->nama_lengkap ?></span>
				<span class="avatar avatar-online">
					<?php $cld = new \Cloudinary\Cloudinary(CLD_CONFIG) ?>
					<img src="<?= $cld->image($user->image) ?>" alt="avatar">
					<i></i>
				</span>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="user-profile.html">
					<i class="ft-user"></i> Edit Profile
				</a>
			  	<div class="dropdown-divider"></div>
			  	<a class="dropdown-item logout" href="javascript:void(0)">
					<i class="ft-power"></i> Logout
				</a>
			</div>
		  </li>
		  <?php else : ?>
			<a href="<?= route_to('register') ?>" class="btn btn-outline-success btn-min-width mr-1">Daftar</a>
			<a href="<?= route_to('login') ?>" class="btn btn-success btn-min-width mr-1">Login</a>
		  <?php endif ?>
		</ul>
	  </div>
	</div>
  </div>
</nav>
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
<div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-fixed navbar-dark navbar-without-dd-arrow navbar-shadow" role="navigation" data-menu="menu-wrapper">
  <div class="navbar-container main-menu-content" data-menu="menu-container">
	<ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
		<li class="dropdown nav-item <?= $menu == 'home' ? 'active' : ''?>">
			<a class="dropdown-toggle nav-link" href="<?= base_url('/') ?>">
				<i class="la la-home"></i>
				<span data-i18n="Lowongan">Home</span>
			</a>
		</li>
		<li class="dropdown nav-item <?= $menu == 'vacancies' ? 'active' : ''?>">
			<a class="dropdown-toggle nav-link" href="<?= route_to('vacancies') ?>">
				<i class="la la-newspaper-o"></i>
				<span data-i18n="Lowongan">Lowongan</span>
			</a>
		</li>
		<?php if(logged_in()) : ?>
			<li class="dropdown nav-item <?= $menu == 'applications' ? 'active' : ''?>">
			<a class="dropdown-toggle nav-link" href="<?= route_to('job_applications') ?>">
				<i class="la la-briefcase"></i>
				<span data-i18n="Lamaran Kerja">Lamaran Kerja</span>
			</a>
		</li>
	  	<?php endif ?>
	</ul>
  </div>
</div>

<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
	  <div class="content-header row">
		<div class="content-header-left col-md-6 col-12 mb-2">
			<?php if(isset($heading)) : ?>
				<h3 class="content-header-title"><?= $heading ?></h3>
			<?php endif ?>
			<?php if(isset($breadcrumb)) : ?>
				<div class="row breadcrumbs-top">
					<div class="breadcrumb-wrapper col-12">
						<?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
					</div>
				</div>
			<?php endif ?>
		</div>
	  </div>
	  <div class="content-body">
		<?php $this->renderSection('content') ?>
	  </div>
  </div>
</div>

<footer class="footer footer-static footer-light navbar-shadow">
  <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2019 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/modern_admin" target="_blank">PIXINVENT</a></span><span class="float-md-right d-none d-lg-block">Hand-crafted & Made with<i class="ft-heart pink"></i><span id="scroll-top"></span></span></p>
</footer>
<!-- END: Footer-->

</body>
<!-- END: Body-->

  <!-- js template -->
  <script src="<?php echo base_url() ?>/vendors/js/vendors.min.js"></script>
  <script src="<?php echo base_url() ?>/vendors/jquery.sticky.js"></script>
  <script src="<?php echo base_url() ?>/js/sweetalert2.min.js"></script>
  <script src="<?php echo base_url() ?>/vendors/js/forms/select/select2.full.min.js"></script>
  <script src="<?php echo base_url('vendors/pnotify/pnotify.custom.min.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/js/extensions/moment.min.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/feather-icons/feather.min.js'); ?>"></script>
  <script src="<?php echo base_url('js/jquery.number.min.js'); ?>"></script>
  <script src="<?php echo base_url() ?>/js/core/app-menu.min2.js"></script>
  <script src="<?php echo base_url() ?>/js/core/app.min.js"></script>
  <script src="<?php echo base_url() ?>/js/app.js"></script>
  <script>
    let date = new Date('04:50:21', 'hh:mm')
  </script>
  <!-- <script src="<?php //echo base_url() ?>/js/scripts/extensions/ex-component-sweet-alerts.min.js"></script> -->

  <?php $this->renderSection('plugin_js') ?>
  <?php $this->renderSection('custom_js') ?>
  <script>
    <?php
    if (isset($_SESSION['form_response_status'])) {
      echo "new PNotify({text: '" . $_SESSION['form_response_message'] . "', type: '" . $_SESSION['form_response_status'] . "'});";
    }
    if ($alertStatus = session()->getFlashData('form_alert_status')) {
      $alertMessage = session()->getFlashData('form_alert_message');
      echo "Swal.fire({icon: '" . $alertStatus . "', html: '" . $alertMessage . "'})";
    }
    ?>
  </script>

</html>