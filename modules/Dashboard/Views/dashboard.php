<?php $this->extend('layout/default') ?>
<?php $this->section('content') ?>
<div class="content-header-left col-md-6 col-12 my-2 breadcrumb-new">
    <h2 class="content-header-title mb-0 d-inline-block"><?php echo $heading ?></h2>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<?php if (isset($pluginJS)) {
    foreach ($pluginJS as $file) {
        echo '<script src="' . $file . '"></script>';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<?php if (isset($customJS)) {
    foreach ($customJS as $file) {
        echo '<script src="' . $file . '?v=' . $_ENV['ASSETV'] . '"></script>';
    }
} ?>
<?php $this->endSection(); ?>