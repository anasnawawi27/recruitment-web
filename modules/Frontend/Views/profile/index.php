<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>

<div class="row">
    
    <div class="col-12">
        <a href="<?= route_to('profile_form', $data->id) ?>" class="btn btn-primary text-white mb-2"><i class="ft-edit"></i> Edit Data</a>
    </div>
    <div class="col-12">
        <?php foreach($profiles as $profile) : ?>
            <div class="card shadow-none">
                <div class="card-header border-bottom">
                    <h4 class="form-section mb-0"><i class="<?= $profile['icon'] ?>"></i> <?= $profile['section'] ?></h4>
                </div>
                <div class="card-body">
                    <div class="form-body">
                    <?php foreach($profile['rows'] as $row) : ?>
                    <div class="form-group row d-flex align-items-center">
                        <label class="col-form-label col-md-4 col-sm-4">
                            <?= $row['label'] ?>
                        </label>
                        <div class="col-md-8">
                            <?php if(
                                $row['label'] == 'ktp' ||
                                $row['label'] == 'File Vaksin 1' ||
                                $row['label'] == 'File Vaksin 2' ||
                                $row['label'] == 'File Vaksin 3' ||
                                $row['label'] == 'Profile Picture'

                            ) : ?>
                                <?php if($row['data']) : ?>
                                    <?php  
                                        $cld = new \Cloudinary\Cloudinary(CLD_CONFIG);
                                        if($row['label'] == 'Profile Picture'){
                                            $image = $cld->image($row['data']);
                                        } else {
                                            $image = $cld->image($row['data'] . '.png');
                                        }
                                    ?>
                                    <img class="img-thumbnail" style="width:200px; height:200px; object-fit: cover" src="<?= $image ?>">
                                <?php else : ?>
                                    <div class="alert alert-warning mb-2" role="alert">
                                        <strong><?= $row['label'] ?> Belum di Upload</strong>
                                    </div>
                                <?php endif ?>
                            <?php else : ?>
                                <h5 class="font-weight-bold mb-0"><span class="d-none d-md-inline">:</span> <?= $row['data'] ?></h5>
                            <?php endif ?>
                        </div>
                    </div>
                    <?php endforeach ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php $this->endSection(); ?>