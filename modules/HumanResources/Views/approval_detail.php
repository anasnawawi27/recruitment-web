<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<?php

use CodeIgniter\I18n\Time; ?>
<div class="d-flex h-100 card pb-5 mb-5 px-2">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h2 class="content-header-title mb-0 d-inline-block"><?php echo $heading ?></h2>
            <div>
                <a href="<?php echo route_to('approvals') ?>" class="btn btn-light text-secondary mr-1">
                    <i class="la la-angle-left"></i>
                    <?php echo lang('Common.back') ?>
                </a>
                <?php if ($data->status == 'draft') : ?>
                    <button class="btn btn-danger mr-1 approval" data-type="cancelled" data-id="<?php echo $data->id ?>">
                        <i class="la la-times-circle"></i>
                        <?php echo lang('Approvals.cancel') ?>
                    </button>
                    <button class="btn btn-success approval" data-type="approved" data-id="<?php echo $data->id ?>">
                        <i class="la la-check-square"></i>
                        <?php echo lang('Approvals.approve') ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-6">
                <div class="row mb-1">
                    <div class="col-4">
                        <?php echo lang('Approvals.employee_id') ?>
                    </div>
                    <div class="col-8">
                        : <?php echo $data->employee_id ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4">
                        <?php echo lang('Approvals.employee') ?>
                    </div>
                    <div class="col-8">
                        : <?php echo $data->fullname ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4">
                        <?php echo lang('Approvals.type') ?>
                    </div>
                    <div class="col-8">
                        : <?php echo ucwords($data->type) ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4">
                        <?php echo lang('Approvals.start_date') ?>
                    </div>
                    <div class="col-8">
                        : <?php echo Time::parse($data->start_date)->toLocalizedString('d MMMM yyyy') ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4">
                        <?php echo lang('Approvals.end_date') ?>
                    </div>
                    <div class="col-8">
                        : <?php echo Time::parse($data->end_date)->toLocalizedString('d MMMM yyyy') ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4">
                        <?php echo lang('Approvals.note') ?>
                    </div>
                    <div class="col-8">
                        : <?php echo $data->note ?? '-' ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4">
                        <?php echo lang('Approvals.status') ?>
                    </div>
                    <div class="col-8">
                        : <span class="font-weight-bold <?php echo $data->status == 'draft' ? 'text-info' : ($data->status == 'approved' ? 'text-success' : 'text-danger') ?>"><?php echo strtoupper($data->status) ?></span>
                    </div>
                </div>
            </div>
            <?php if ($data->file) : ?>
                <div class="col-6">
                    <label>Document</label>
                    <img src="<?php echo $data->file ?>" class="img-thumbnail">
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                <img src="<?php echo base_url('images/illustrations/leaves.png') ?>" alt="">
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
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