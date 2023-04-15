<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>
<div class="row">
    <?php if($vacancies) : ?>
        <?php foreach($vacancies as $vacancy) : ?>
            <?php if($vacancy->tampil == '1') : ?>
                <div class="col-12 col-md-4">
                    <a href="<?= base_url('vacancy/detail/' . $vacancy->id) ?>">
                        <div class="card">
                            <?php
                                $cld = new \Cloudinary\Cloudinary(CLD_CONFIG);
                            ?>
                            <div class="card-img-top img-fluid" style="height: 150px; width: 100%; background-size: cover; background-image: url(<?= !$vacancy->gambar ? base_url('images/illustrations/lowongan-default.png') : $cld->image($vacancy->gambar) ?>)">
            
                            </div>
                            <div class="card-body text-secondary">
                                <h4 class="font-weight-bold mb-1"><?= $vacancy->posisi ?></h4>
                                <div class="ellipsis-text">
                                    <p><?= strip_tags($vacancy->qualifikasi) ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    <?php else : ?>
    <div class="col-12 text-center">
        <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
    </div>
    <?php endif ?>
</div>
<?php $this->endSection(); ?>
