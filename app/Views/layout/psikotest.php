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

<!-- BEGIN: Main Menu-->
<div class="header-navbar  navbar navbar-horizontal navbar-fixed navbar-dark navbar-without-dd-arrow navbar-shadow" role="navigation" data-menu="menu-wrapper">
  <div class="navbar-container main-menu-content d-flex justify-content-end align-items-end text-right w-100" data-menu="menu-container">
	<h1 class="white">Sisa Waktu : </h1>
	<h1 class="white font-weight-bold counter"> &nbsp; 00:00:00:00</h1>
  </div>
</div>

<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
	  <div class="content-body">
		<?php $this->renderSection('content') ?>
	  </div>
  </div>
</div>

</body>
<!-- END: Body-->

  <!-- js template -->
  <script src="<?php echo base_url() ?>/vendors/js/vendors.min.js"></script>
  <script src="<?php echo base_url() ?>/vendors/jquery.sticky.js"></script>
  <script src="<?php echo base_url() ?>/js/sweetalert2.min.js"></script>
  <script src="<?php echo base_url() ?>/js/scrollfix.js"></script>
  <script src="<?php echo base_url() ?>/vendors/js/forms/select/select2.full.min.js"></script>
  <script src="<?php echo base_url('vendors/pnotify/pnotify.custom.min.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/js/extensions/moment.min.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/feather-icons/feather.min.js'); ?>"></script>
  <script src="<?php echo base_url() ?>/js/core/app-menu.min2.js"></script>
  <script src="<?php echo base_url() ?>/js/core/app.min.js"></script>
  <script src="<?php echo base_url() ?>/js/app.js"></script>
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