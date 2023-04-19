<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>
<div class="row">
    <?php if($applications) : ?>
      <?php foreach($applications as $application) : ?>
        <div class="col-12 col-md-4">
            <a href="<?= base_url('job-application/detail/' . $application->id) ?>">
            <div class="card pull-up">
              <div class="card-content">
                <div class="card-body">
                  <div class="media d-flex">
                    <div class="media-body text-left">
                      <span class="badge-status <?= $application->status ?> text-uppercase">
                        <?= $application->status ?>
                      </span>
                      <h3><?= $application->posisi ?></h3>
                    </div>
                    <div class="align-self-center">
                      <i class="icon-briefcase warning font-large-2 float-right"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </a>
        </div>
        <?php endforeach ?>
    <?php else : ?>
    <div class="col-12 text-center">
        <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
    </div>
    <?php endif ?>
</div>
<?php $this->endSection(); ?>