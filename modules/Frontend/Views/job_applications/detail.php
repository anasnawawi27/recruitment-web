<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>
<?php use CodeIgniter\I18n\Time; ?>
<div class="row">
    <?php if($data) : ?>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <a href="<?= route_to('job_applications') ?>" class="mr-1 mb-1 btn btn-outline-secondary text-left border-0 btn-min-width">
                    <i class="la la-arrow-left"></i> Kembali
                </a>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="d-flex mb-2 align-items-center">
                            <i class="icon-briefcase secondary font-large-1"></i>
                            <h3 class="font-weight-bolder mb-0 ml-1"><?= $data->posisi ?></h3>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <span class="badge-status mb-0 <?= $data->status ?> text-uppercase">
                                <?= $data->status ?>
                            </span>
                            <span class="font-weight-bolder d-inline-block mx-1"> | </span>
                            <span> Di-Apply pada :
                            <?php
                                $time = Time::parse($data->created_at, 'Asia/Jakarta');
                                echo $time->toLocalizedString('d MMMM, yyyy');
                            ?>
                            </span>
                        </div>
                        <img class="img-fluid mt-3" src="<?= base_url('images/illustrations/worker.png') ?>" alt="worker">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <?php if($data->status == 'applied') : ?>
                    <h5 class="font-weight-bolder mb-1">Tahap Psikotest</h5>
                    <div class="card shadow-none border-secondary border-lighten-5">
                        <div class="card-body">
                            <div class="d-md-flex justify-content-between">
                                <div class="form-group">
                                    <label>Waktu Pengerjaan</label>
                                    <h6 class="font-weight-bolder"><?= $data->waktu_pengerjaan ?> Menit</h6>
                                </div>
                                <div class="form-group">
                                    <label>Kategori Soal</label>
                                    <h6 class="font-weight-bolder"></h6>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Soal</label>
                                    <h6 class="font-weight-bolder"></h6>
                                </div>
                                <div class="form-group">
                                    <label>Point Persoal</label>
                                    <h6 class="font-weight-bolder"><?= $data->point_persoal ?></h6>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-block">
                                Kerjakan Sekarang
                            </button>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php else : ?>
    <div class="col-12 text-center">
        <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
    </div>
    <?php endif ?>
</div>
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<?php $this->endSection(); ?>
