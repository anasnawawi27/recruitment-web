<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>
<div class="row">
    <?php
    $current_date = date('Y-m-d');
    if($current_date <= $data->batas_tanggal) : ?>
            <div class="col-12">
                <div class="card">
                    <?php
                        $cld = new \Cloudinary\Cloudinary(CLD_CONFIG);
                    ?>
                    <div class="d-md-flex">
                        <div class="card-img-top img-fluid side-img" style="background-size: cover; background-image: url(<?= !$data->gambar ? base_url('images/illustrations/lowongan-default.png') : $cld->image($data->gambar) ?>)"></div>
                        <div class="card-body text-secondary">
                            <p>We Are Hiring !</p>
                            <h4 class="font-weight-bold mb-0"><?= $data->posisi ?></h4>
                            <hr>
                            <?= $data->deskripsi ?>
                            <div class="d-flex">
                                <a href="<?= base_url('vacancy/apply/' . $data->id) ?>" class="btn btn-info btn-icon mr-1 btn-block">
                                    <i class="la la-file-text"></i>    
                                    Apply
                                </a>
                                <a href="<?= route_to('vacancies') ?>" class="btn btn-outline-secondary btn-min-width">
                                    <i class="la la-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
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
<style>
    .side-img{
        width: 30%;
        border-radius: 5px 0 0 5px;
        background-position: right; 
    }

    @media only screen and (max-width: 600px) {
        .side-img {
            height: 190px!important;
            width: 100%!important; 
            border-radius: 5px!important;
            background-position: top!important; 
        }
    }
</style>
<?php $this->endSection(); ?>
