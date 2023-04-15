<?php $this->extend('layout/default') ?>
<?php $this->section('content') ?>
<style>
    .header-profile-attendance {
        background-color: #F6F6F6;
    }

    .header-profile-attendance .h4 {
        margin-left: 25px;
    }

    .ft-clock {
        font-size: 15px
    }

    .badge-success-light {
        color: #00E090;
        font-weight: bold;
        background-color: #D5F6EA;
    }

    .badge-danger-light {
        color: #FF4961;
        font-weight: bold;
        background-color: #FFE0E4;
    }

    .badge-secondary-light {
        color: #4C4E5A;
        font-weight: bold;
        background-color: #D0D1D4;
    }
</style>
<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
    <h2 class="content-header-title mb-0 d-inline-block"><?php echo $heading ?></h2>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-7">
        <div class="card card-body position-relative">
            <div class="mb-2">
                <button class="btn btn-secondary branch-location"><i class="ft-map"></i> <?php echo lang('Common.workplace') ?></button>
                <button class="btn btn-secondary current-location"><i class="ft-map-pin"></i> <?php echo lang('Common.current_position') ?></button>
            </div>
            <div id="map-attendance" style="height: 450px"></div>
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <div class="row mt-2">
                <div class="col-6 pr-0" style="padding-right:7px!important">
                    <button id="clock-in" data-type="in" class="btn btn-success btn-block">Clock In</button>
                </div>
                <div class="col-6 pl-0" style="padding-left:7px!important">
                    <button id="clock-out" data-type="out" class="btn btn-danger btn-block" disabled>Clock Out</button>
                </div>
            </div>
            <div class="mt-1">
                <span>
                    <h6 class="font-weight-bold">Lokasi Saat ini : </h6>
                    <span class="display-location"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5">
        <?php $avatar = $user->image; ?>
        <div class="card card-body p-1">
            <div class="header-profile-attendance p-1 rounded-top">
                <span>
                    <i class="ft-calendar"></i>
                    <h4 class="font-weight-bold d-inline-block"><?php echo lang('Attendances.profile_section_header') ?></h4>
                </span>
            </div>
            <div class="text-center">
                <div class="users-avatar-shadow rounded-circle" style="width:70px; height:70px; margin: 20px auto; background-image: url(<?php echo $avatar ?? "https://via.placeholder.com/30x30" ?>); background-size:cover; background-position:center"></div>
                <h5 class="font-weight-bold"><?php echo $user->fullname ?></h5>
                <h6 class="text-light"><?php echo $user->job_title_name ?></h6>
                <div id="status-attendance"><span class="badge badge-<?php echo !isset($attendance->status) ? 'secondary-light' : ($attendance->status == 'on time' ? 'success-light' : 'danger-light')  ?> badge-pill">
                        <?php echo $attendance && $attendance->status ? strtoupper($attendance->status) : 'Not Attendance' ?></span>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6 pl-3" style="border-right: 0.5px solid #E5E5E5">
                    <h6 class="font-weight-bold"><?php echo lang('Attendances.clock_in') ?></h6>
                    <span><i class="ft-clock"></i> <span class="clock-in text-muted"> <?php echo $attendance && $attendance->clock_in ? substr($attendance->clock_in, 0, 5) :  '-- : --' ?> </span></span>
                </div>
                <div class="col-6 pl-3">
                    <h6 class="font-weight-bold"><?php echo lang('Attendances.clock_in') ?></h6>
                    <span><i class="ft-clock"></i> <span class="clock-out text-muted"> <?php echo $attendance && $attendance->clock_out ? substr($attendance->clock_out, 0, 5) :  '-- : --' ?> </span></span>
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

<?php $this->section('plugin_js'); ?>
<?php if (isset($pluginJS)) {
    foreach ($pluginJS as $file) {
        echo '<script src="' . $file . '"></script>';
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

<?php $this->section('custom_js'); ?>
<script>
    let branchName = '<?php echo $user->branch_name ?>';
    let branchAddress = '<?php echo $user->branch_address ?>';
    let branchLatitude = '<?php echo $user->branch_latitude ?>';
    let branchLongitude = '<?php echo $user->branch_longitude ?>';
</script>
<?php if (isset($customJS)) {
    foreach ($customJS as $file) {
        echo '<script src="' . $file . '?v=' . $_ENV['ASSETV'] . '"></script>';
    }
} ?>
<?php $this->endSection(); ?>