<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<?php

use CodeIgniter\I18n\Time; ?>
<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
    <h2 class="content-header-title mb-0 d-inline-block"><?php echo $heading ?></h2>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
        </div>
    </div>
</div>
<div class="d-flex h-100 card pb-5 mb-5 px-2">
    <div class="card-body">
        <ul class="nav nav-tabs nav-underline">
            <li class="nav-item">
                <a href="#tab-personal" class="nav-link show active" data-toggle="tab"> <i class="ft-user"></i> <?php echo lang('Users.personal'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#tab-work" class="nav-link" data-toggle="tab"> <i class="ft-briefcase"></i> <?php echo lang('Users.work'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#tab-account" class="nav-link" data-toggle="tab"> <i class="ft-lock"></i> <?php echo lang('Users.account'); ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active p-2" id="tab-personal">
                <div class="row">
                    <div class="col-2 col-md-3">
                        <div class="bg-white rounded mr-2" style="width: 150px; height: 150px; border: 0.5px solid #D6D4D7; padding: 3px; box-sizing:content-box">
                            <div class="rounded" style="width: 150px; height: 150px; background-image: url('<?php echo $detail->image ?? 'https://via.placeholder.com/100x100' ?>'); background-position:center; background-size: cover"></div>
                        </div>
                    </div>
                    <div class="col-10 col-md-9">
                        <div class="row">
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.employee_id') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->employee_id ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.id_number') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->id_number ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.fullname') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->fullname ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.gender') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo ucwords($detail->gender) ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.place_of_birth') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo ucwords($detail->place_of_birth) ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.date_of_birth') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo Time::parse($detail->date_of_birth)->toLocalizedString('d MMMM yyyy') ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.blood_group') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->blood_group ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.email') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->email ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.phone') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->phone ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Users.address') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->address ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade p-2" id="tab-work">
                <div class="row">
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.job_title_id') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo $detail->job_title ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.branch_id') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo $detail->branch ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.join_date') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo Time::parse($detail->join_date)->toLocalizedString('d MMMM yyyy') ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.driving_license_num') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo $detail->driving_license_number ? $detail->driving_license_number : '-' ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.driving_license_exp') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo $detail->driving_license_expired ? Time::parse($detail->driving_license_expired)->toLocalizedString('d MMMM yyyy') : '-' ?></h6>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade p-2" id="tab-account">
                <div class="row">
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.username') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo $detail->username ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.role') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo ucwords($group->role) ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.status') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary"><span class="badge badge-<?php echo $detail->active == 1 ? 'success' : 'danger' ?>"><?php echo $detail->active == 1 ? 'ACTIVE' : 'NONACTIVE' ?></span></h6>
                    </div>
                </div>
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