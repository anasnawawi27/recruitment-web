<?php $this->extend('layout/default') ?>
<?php $this->section('content')?>
<div class="content-header-left col-md-6 col-12 my-2 breadcrumb-new">
    <h2 class="content-header-title mb-0 d-inline-block"><?php echo $heading ?></h2>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
        </div>
    </div>
</div>
<?php echo $this->include('table') ?>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.css">
<link href="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/sticky-header/bootstrap-table-sticky-header.css" rel="stylesheet">
<!-- <link href="<?php //echo base_url('vendors/multiselect/bootstrap-multiselect.min.css'); ?>" rel="stylesheet" type="text/css"/> -->
<?php if(isset($pluginCSS)) {
    foreach($pluginCSS as $file) {
        echo '<link href="'.$file.'" rel="stylesheet" type="text/css">';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/cookie/bootstrap-table-cookie.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>
<!-- <script src="<?php //echo base_url('vendors/multiselect/bootstrap-multiselect.min.js'); ?>" type="text/javascript"></script> -->
<?php if(isset($pluginJS)) {
    foreach($pluginJS as $file) {
        echo '<script src="'.$file.'"></script>';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<?php if(isset($customCSS)) {
    foreach($customCSS as $file) {
        echo '<link href="'.$file.'?v='.$_ENV['ASSETV'].'" rel="stylesheet" type="text/css">';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<script src="<?php echo base_url('js/table.js?v='.$_ENV['ASSETV']); ?>"></script>
<?php if(isset($customJS)) {
    foreach($customJS as $file) {
        echo '<script src="'.$file.'?v='.$_ENV['ASSETV'].'"></script>';
    }
} ?>
<?php $this->endSection(); ?>
