<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<div class="h-100 pb-5 mb-3 px-2" style="border-radius: 0">
    <div class="card shadow-none mt-2">
        <div class="page-header d-flex w-100 justify-content-between flex-wrap p-2">
            <div class="d-flex align-items-center">
                <h4 class="font-weight-bold"><?php echo (isset($heading) ? $heading : lang('heading')); ?></h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="d-flex align-items-center">
                <h5 class="font-weight-bolder mb-0 mr-1">Kategori Soal :</h5>
                <h5 class="mb-0"><?= $type->kategori ?></h5>
            </div>

            <div class="mt-2 border-top">
                <h5 class="font-weight-bolder mt-1">Pertanyaan</h5>
                <?php if(count($data) > 0) : ?>
                    <?php foreach($data as $index => $question) : ?>
                        <div class="card shadow-none question question-<?= $index ?>">
                            <div class="card-body">
                                <div class="d-flex w-100">
                                    <h5 class="font-weight-bolder mr-2"><?= $index + 1 ?>. </h5>
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex">
                                            <?php
                                            $cld = new \Cloudinary\Cloudinary(CLD_CONFIG);
                                                if ($question->gambar) {
                                                    $image = $cld->image($question->gambar);
                                                    $file_preview = '<img class="img-thumbnail mb-2" style="width:200px; height:200px; object-fit: contain" src="' . $image . '">';
                                                } else {
                                                    $file_preview = '';
                                                }
                                            ?>
                                            <div class="ml-1">
                                                <?php if($file_preview ) : ?>
                                                    <?= $file_preview ?>
                                                <?php endif ?>
                                                <h5 class="font-weight-bolder"><?= $question->pertanyaan ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="font-weight-bolder ml-2 mt-2 mb-1">Pilihan :</h6>
                                <?php
                                    $alphabet = range('A', 'Z');
                                    $options = json_decode($question->options)
                                ?>
                                <?php foreach($options as $i => $option) : ?>
                                    <div class="row opsi-<?= $i ?>">
                                        <div class="col-12 pl-3 option-<?= $i ?> opsi-<?= $index . '-' . $i ?>">
                                            <div class="card mb-0">
                                                <div class="card-body pt-0 px-0">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <label class="alpha-radio">
                                                                <input type="radio" disabled="disabled" <?= $alphabet[$i] == $question->jawaban ? 'checked' : '' ?> name="answers[<?= $index ?>]" value="<?= $alphabet[$i] ?>"/>
                                                                <span><?= $alphabet[$i] ?></span>
                                                            </label>
                                                        </div>
                                                        <?php
                                                            if (isset($option->gambar_id)) {
                                                                $image = $cld->image($option->gambar_id);
                                                                $preview = '<img class="img-thumbnail mb-2" style="width:200px; height:200px; object-fit: contain" src="' . $image . '">';                                        
                                                            } else {
                                                                $preview = '';
                                                            }
                                                        ?>
                                                        <div class="d-flex justify-content-between w-100">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="ml-1">
                                                                    <?php if($preview ) : ?>
                                                                        <?= $preview ?>
                                                                    <?php endif ?>
                                                                    <h6><?= $option->opsi ?></h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else : ?>
                    <div class="w-100 text-center">
                        <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
                    </div>
                <?php endif ?>
            </div>

            <div class="row bg-white rounded w-100 p-2 m-0" style="border-radius: 5px">
                <div class="col-12 text-right">
                    <a href="<?= route_to('questions') ?>" class="btn btn-outline-secondary text-left border-0 px-2 float-left">
                        <i class="la la-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<style>
    .border-top{
        border-top: 1px solid #e5e5e5!important;
    }

    label.alpha-radio input[type="radio"] {
        width: 30px;
        height: 30px;
        border-radius: 15px;
        border: 2px solid #1FBED6;
        background-color: white;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    label.alpha-radio input[type="radio"]:focus {
        outline: none; 
    }

    label.alpha-radio input[type="radio"]:checked {
        background-color: #1FBED6;
    }

    label.alpha-radio input[type="radio"]:checked ~ span:first-of-type {
        color: white;
    }

    label.alpha-radio span:first-of-type {
        position: relative;
        left: -20px;
        top: 0;
        font-size: 15px;
        color: #1FBED6;
    }

    label.alpha-radio{
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    label.alpha-radio span {
        position: relative;
        top: -12px;
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<?php $this->endSection(); ?>