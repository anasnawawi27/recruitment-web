<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<div class="d-flex h-100 card pb-5 mb-5 px-2" style="border-radius: 0">
    <div class="page-header d-flex justify-content-between flex-wrap p-2">
        <div class="d-flex align-items-center">
            <h4 class="font-weight-bold"><?php echo (isset($heading) ? $heading : lang('heading')); ?></h4>
        </div>
        <?php if (!isset($form['is_form_report'])) { ?>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
            </div>
        <?php } ?>
    </div>
    <form id="<?php echo isset($form['id']) ? $form['id'] : 'form'; ?>" <?php echo (isset($form['is_form_report']) ? 'class="no-ajax target-blank"' : ''); ?> action="<?php echo $form['action']; ?>" method="post" enctype="multipart/form-data">
        <?php if (isset($form['form_tab'])) { ?>
            <div class="card-body">
                <ul class="nav nav-tabs nav-underline">
                    <?php foreach ($form['build'] as $key => $build) { ?>
                        <li class="nav-item">
                            <a href="#tab-<?php echo $key; ?>" class="nav-link <?php echo ($key == 0) ? 'show active' : ''; ?>" data-toggle="tab"><?php echo isset($build['icon']) ? $build['icon'] : ''; ?> <?php echo $build['title']; ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <?php foreach ($form['build'] as $key => $build) { ?>
                        <div class="tab-pane fade <?php echo ($key == 0) ? 'show active' : ''; ?> p-3" id="tab-<?php echo $key; ?>">
                            <?php echo $build['build']; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="card-body">
                <?php echo $form['build']; ?>
            </div>
        <?php } ?>
    </form>
</div>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<link href="<?php echo base_url('vendors/bootstrap-fileinput/bootstrap-fileinput.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('vendors/multiselect/bootstrap-multiselect.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<?php if (isset($pluginCSS)) {
    foreach ($pluginCSS as $file) {
        echo '<link href="' . $file . '" rel="stylesheet" type="text/css">';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<?php if (isset($customCSS)) {
    foreach ($customCSS as $file) {
        echo '<link href="' . $file . '?v=' . $_ENV['ASSETV'] . '" rel="stylesheet" type="text/css">';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<script src="<?php echo base_url('vendors/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/jquery.form.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/bootstrap-fileinput/bootstrap-fileinput.js'); ?>"></script>
<script src="<?php echo base_url('vendors/multiselect/bootstrap-multiselect.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/summernote.min.js'); ?>"></script>
<?php if (isset($pluginJS)) {
    foreach ($pluginJS as $file) {
        echo '<script src="' . $file . '"></script>';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<script src="<?php echo base_url('js/form.js'); ?>"></script>
<?php if (isset($customJS)) {
    foreach ($customJS as $file) {
        echo '<script src="' . $file . '?v=' . $_ENV['ASSETV'] . '"></script>';
    }
} ?>

<?php $this->endSection(); ?>