<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
  <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
  <meta name="author" content="PIXINVENT">
  <title><?php echo $title ?></title>
  <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
  <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="<?php echo base_url('vendors/css/vendors.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/bootstrap-extended.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/components.min.css') ?>">
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
  <script src="<?php echo base_url('js/language/en.js'); ?>"></script>
</head>

<body class="vertical-layout vertical-menu-modern 1-column  bg-full-screen-image blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">
        <section class="row flexbox-container">
          <div class="col-12 d-flex align-items-center justify-content-center">
            <?php $this->renderSection('content') ?>
          </div>
        </section>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url('vendors/js/vendors.min.js') ?>"></script>
  <script src="<?php echo base_url('vendors/feather.min.js') ?>"></script>
  <script src="<?php echo base_url('vendors/js/forms/select/select2.min.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/jquery.validate.min.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/jquery.form.min.js'); ?>"></script>
  <script src="<?php echo base_url('js/form.js'); ?>"></script>
  <script src="<?php echo base_url('js/app.js'); ?>"></script>
  <?php $this->renderSection('custom_js') ?>

</body>

</html>