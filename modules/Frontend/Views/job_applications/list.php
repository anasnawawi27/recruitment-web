<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>
<?php use CodeIgniter\I18n\Time; ?>
<div class="row">
    <?php if($applications) : ?>
      <?php foreach($applications as $application) : ?>
        <div class="col-12 col-md-4">
            <a href="<?= base_url('job-application/detail/' . $application->id) ?>">
            <div class="card pull-up">
              <div class="card-content">
                <div class="card-body">
                  <div class="media">
                    <div class="media-body text-left">
                      <p class="text-secondary">Posisi</p>
                      <div class="d-flex align-items-center">
                        <i class="icon-briefcase warning font-large-1 float-right"></i>
                        <h5 class="ml-1 mb-0 font-weight-bold"><?= $application->posisi ?></h5>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer p-1">
                  <div class="d-flex justify-content-between align-items-cen">
                    <div>
                      <span class="badge-status mb-0 status-<?= $application->status ?> text-capitalize">
                        <?= $application->status ?>
                      </span>
                    </div>
                    <div>
                      <small class="text-muted">Di-<em>apply</em> pada <?=  Time::parse($application->created_at)->toLocalizedString('d MMMM yyyy') ?></small>
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