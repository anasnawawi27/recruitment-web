<?php $this->extend('layout/common') ?>
<?php $this->section('content')?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body text-center">
                <img class="mb-3" height="200px" src="https://www.energyfit.com.mk/wp-content/plugins/ap_background/images/default/default_large.png">
                <h3 class="font-weight-bold">Mohon Maaf</h3>
                <p>Anda belum memenuhi kualifikasi kandidat yg kami cari</p>
                <a href="<?= route_to('vacancies') ?>" class="btn btn-primary btn-glow round">Apply Lowongan Lain</a>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<?php $this->endSection(); ?>
