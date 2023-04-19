<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<form id="form-question" method="post" action="<?= route_to('question_save') ?>" enctype="multipart/form-data">
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
        <div class="card-body">
            <input type="hidden" name="id" value="">
            <div class="form-group row">
                <label for="kategori" class="col-form-label col-md-2 col-sm-4">
                    Kategori
                </label>
                <div class="col-md-5">
                    <select class="form-control select2" id="kategori" name="id_kategori">
                        <?php foreach($types as $type) : ?>
                        <option value="<?= $type->id ?>"><?= $type->kategori ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <h5 class="font-weight-bolder mt-1">Pertanyaan</h5>
    <div class="questions">
        <div class="card shadow-none question question-0">
            <div class="card-body">
                <div class="d-flex w-100">
                    <h5 class="font-weight-bolder mr-2">1. </h5>
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <div class="form-group">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                    <div>
                                        <span class="btn btn-raised btn-sm btn-outline-secondary btn-file cursor-pointer">
                                            <span class="fileinput-new">
                                                <i class="la la-image"></i>
                                            </span>
                                            <span class="fileinput-exists">
                                                <i class="la la-image"></i>
                                            </span>
                                            <input class="upload-file" type="file" name="image_questions[0]" data-param="2" accept="image/*">
                                        </span>
                                        <input type="hidden" name="delete_image" value="">
                                        <a href="#" class="btn btn-raised btn-sm btn-danger fileinput-exists delete_image" data-dismiss="fileinput">
                                            <i class="la la-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-1">
                                <textarea cols="115" name="questions[0]" class="form-control" placeholder="Tambah Pertanyaan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <h6 class="font-weight-bolder ml-2 mt-2 mb-1">Jawaban :</h6>
                <div class="row opsi-0">
                    <div class="col-12 pl-3 option-0 opsi-0-0">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <label class="alpha-radio">
                                            <input type="radio" checked name="answers[0]" value="A"/>
                                            <span>A</span>
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-group mb-0">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                                    <div>
                                                        <span class="btn btn-raised btn-sm btn-outline-secondary btn-file cursor-pointer">
                                                            <span class="fileinput-new">
                                                                <i class="la la-image"></i>
                                                            </span>
                                                            <span class="fileinput-exists">
                                                                <i class="la la-image"></i>
                                                            </span>
                                                            <input class="upload-file" type="file" name="image_options[0][0]" data-param="2" accept="image/*">
                                                        </span>
                                                        <input type="hidden" name="delete_image" value="">
                                                        <a href="#" class="btn btn-raised btn-sm btn-danger fileinput-exists delete_image" data-dismiss="fileinput">
                                                            <i class="la la-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-1">
                                                <textarea cols="100" class="form-control" name="options[0][0]" placeholder="Jawaban"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="button" data-index="0" class="btn btn-glow btn-warning round btn-raised add-option">
                            <i class="la la-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-right">
            <button type="button" class="btn btn-warning btn-raised save">Simpan</button>
            <button type="button" class="btn btn-success btn-raised add-question">Tambah Soal</button>
        </div>
    </div>
</div>
</form>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<link href="<?php echo base_url('vendors/bootstrap-fileinput/bootstrap-fileinput.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('vendors/multiselect/bootstrap-multiselect.min.css'); ?>" rel="stylesheet" type="text/css" />
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<style>
    .border{
        border: 1px solid #e5e5e5!important;
    }

    label.alpha-radio input[type="radio"] {
        width: 30px;
        height: 30px;
        border-radius: 15px;
        border: 2px solid #1FBED6;
        background-color: white;
        -webkit-appearance: none; /*to disable the default appearance of radio button*/
        -moz-appearance: none;
    }

    label.alpha-radio input[type="radio"]:focus { /*no need, if you don't disable default appearance*/
        outline: none; /*to remove the square border on focus*/
    }

    label.alpha-radio input[type="radio"]:checked { /*no need, if you don't disable default appearance*/
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
<script src="<?php echo base_url('vendors/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/jquery.form.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/bootstrap-fileinput/bootstrap-fileinput.js'); ?>"></script>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<script src="<?php echo base_url('js/form.js'); ?>"></script>
<script src="<?php echo base_url('js/modules/question.js'); ?>"></script>
<?php $this->endSection(); ?>