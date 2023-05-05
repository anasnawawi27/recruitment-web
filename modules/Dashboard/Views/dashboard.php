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
<div class="content-body p-2">
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="primary"><?= $total_lowongan ?></h3>
                                <h6>Lowongan</h6>
                            </div>
                            <div>
                                <i class="la la-briefcase primary font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="warning"><?= $total_pelamar ?></h3>
                                <h6>Pelamar</h6>
                            </div>
                            <div>
                                <i class="la la-user warning font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="success"><?= $total_lamaran ?></h3>
                                <h6>Lamaran Kerja</h6>
                            </div>
                            <div>
                                <i class="la la-folder-open success font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="danger"><?= $total_kategori ?></h3>
                                <h6>Kategori Soal</h6>
                            </div>
                            <div>
                                <i class="la la-file-text danger font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex h-100 card px-2">
    <div class="card-body p-0">
        <h3 class="py-2">Lamaran Kerja</h3>
        <div class="card-content collapse show">
            <div class="card-body p-0">
                <canvas id="jobApplication-chart" height="400"></canvas>
            </div>
        </div>
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
<script>
    var jobApplications = JSON.parse('<?php echo json_encode($jobApplications) ?>');
</script>
<?php if (isset($customJS)) {
    foreach ($customJS as $file) {
        echo '<script src="' . $file . '?v=' . $_ENV['ASSETV'] . '"></script>';
    }
} ?>
<?php $this->endSection(); ?>