<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<?php

use CodeIgniter\I18n\Time; ?>
<div class="content-header-left col-md-6 col-12 my-2 breadcrumb-new">
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
                <a href="#tab-personal" class="nav-link show active" data-toggle="tab"> <i class="ft-user"></i> <?php echo lang('Applicants.personal'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#tab-document" class="nav-link" data-toggle="tab"> <i class="ft-file-text"></i> <?php echo lang('Applicants.document'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#tab-account" class="nav-link" data-toggle="tab"> <i class="ft-lock"></i> <?php echo lang('Applicants.account'); ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active p-2" id="tab-personal">
                <div class="row">
                    <div class="col-2 col-md-3">
                        <?php $cld = new \Cloudinary\Cloudinary(CLD_CONFIG); ?>
                        <div class="bg-white rounded mr-2" style="width: 150px; height: 150px; border: 0.5px solid #D6D4D7; padding: 3px; box-sizing:content-box">
                            <div class="rounded" style="width: 150px; height: 150px; background-image: url('<?php echo $account->image ? $cld->image( $account->image) : 'https://via.placeholder.com/100x100' ?>'); background-position:center; background-size: cover"></div>
                        </div>
                    </div>
                    <div class="col-10 col-md-9">
                        <div class="row">
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Applicants.fullname') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->nama_lengkap ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Applicants.gender') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary text-capitalize">: <?php echo $detail->jenis_kelamin ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Applicants.birthplace') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->tempat_lahir ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Applicants.birthdate') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo Time::parse($detail->tanggal_lahir)->toLocalizedString('d MMMM yyyy') ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Applicants.email') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo ucwords($detail->email) ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Applicants.phone_1') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->no_handphone_1 ?></h6>
                            </div>
                            <div class="col-2 col-md-3">
                                <h6 class="text-muted"><?php echo lang('Applicants.phone_2') ?></h6>
                            </div>
                            <div class="col-10 col-md-9">
                                <h6 class="text-secondary">: <?php echo $detail->no_handphone_2 ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade p-2" id="tab-document">
                <?php $cld = new \Cloudinary\Cloudinary(CLD_CONFIG); ?>
                <div class="row">
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Applicants.ktp') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        : <?php echo $detail->ktp ? '<img class="img-thumbnail mb-2" style="width:200px; height:200px; object-fit: cover" src="' . $cld->image($detail->ktp) . '">' : '-' ?>
                        <?php if($detail->ktp) : ?>
                            <br>
                            <a href="<?= str_replace('v1', 'fl_attachment', $cld->image($detail->ktp)) ?>" class="btn btn-primary mb-2" download><i class="ft-download"></i> Download</a>
                        <?php endif ?>
                    </div>
                    <div class="col-2 col-md-3 mt-3">
                        <h6 class="text-muted"><?php echo lang('Applicants.vaksin_date_1') ?></h6>
                    </div>
                    <div class="col-10 col-md-9 mt-3">
                        <h6 class="text-secondary">: <?php echo $detail->tanggal_vaksin_1 ? Time::parse($detail->tanggal_vaksin_1)->toLocalizedString('d MMMM yyyy') : '-' ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Applicants.vaksin_1') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        : <?php echo $detail->file_vaksin_1 ? '<img class="img-thumbnail mb-2" style="width:200px; height:200px; object-fit: cover" src="' . $cld->image($detail->file_vaksin_1 . '.png') . '">' : '-' ?>
                        <?php if($detail->file_vaksin_1) : ?>
                            <br>
                            <a href="<?= str_replace('v1', 'fl_attachment',$cld->image($detail->file_vaksin_1 . '.png')) ?>" class="btn btn-primary mb-2" download><i class="ft-download"></i> Download</a>
                        <?php endif ?>
                    </div>
                    <div class="col-2 col-md-3 mt-3">
                        <h6 class="text-muted"><?php echo lang('Applicants.vaksin_date_2') ?></h6>
                    </div>
                    <div class="col-10 col-md-9 mt-3">
                        <h6 class="text-secondary">: <?php echo $detail->tanggal_vaksin_2 ? Time::parse($detail->tanggal_vaksin_2)->toLocalizedString('d MMMM yyyy') : '-' ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Applicants.vaksin_2') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        : <?php echo $detail->file_vaksin_2 ? '<img class="img-thumbnail mb-2" style="width:200px; height:200px; object-fit: cover" src="' . $cld->image($detail->file_vaksin_2 . '.png') . '">' : '-' ?>
                        <?php if($detail->file_vaksin_2) : ?>
                            <br>
                            <a href="<?= str_replace('v1', 'fl_attachment',$cld->image($detail->file_vaksin_2 . '.png')) ?>" class="btn btn-primary mb-2" download><i class="ft-download"></i> Download</a>
                        <?php endif ?>
                    </div>
                    <div class="col-2 col-md-3 mt-3">
                        <h6 class="text-muted"><?php echo lang('Applicants.vaksin_date_3') ?></h6>
                    </div>
                    <div class="col-10 col-md-9 mt-3">
                        <h6 class="text-secondary">: <?php echo $detail->tanggal_vaksin_3 ? Time::parse($detail->tanggal_vaksin_3)->toLocalizedString('d MMMM yyyy') : '-' ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Applicants.vaksin_3') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        : <?php echo $detail->file_vaksin_3 ? '<img class="img-thumbnail mb-2" style="width:200px; height:200px; object-fit: cover" src="' . $cld->image($detail->file_vaksin_3 . '.png') . '">' : '-' ?>
                        <?php if($detail->file_vaksin_3) : ?>
                            <br>
                            <a href="<?= str_replace('v1', 'fl_attachment',$cld->image($detail->file_vaksin_3 . '.png')) ?>" class="btn btn-primary mb-2" download><i class="ft-download"></i> Download</a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade p-2" id="tab-account">
                <div class="row">
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.username') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo $account->username ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.role') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary">: <?php echo ucwords($account->role) ?></h6>
                    </div>
                    <div class="col-2 col-md-3">
                        <h6 class="text-muted"><?php echo lang('Users.status') ?></h6>
                    </div>
                    <div class="col-10 col-md-9">
                        <h6 class="text-secondary"><span class="badge badge-<?php echo $account->active == 1 ? 'success' : 'danger' ?>"><?php echo $account->active == 1 ? 'ACTIVE' : 'NONACTIVE' ?></span></h6>
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