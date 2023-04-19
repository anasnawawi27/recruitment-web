<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-none">
            <div class="card-header border-bottom">
                <h4 class="font-weight-bold text-secondary mb-0">Apply Lamaran Kerja</h4>
            </div>
            <div class="card-body">
                <form id="apply" method="post" enctype="multipart/form-data">
                    <div class="row justify-content-md-center">
                        <div class="col-md-6">
                            <div class="form-body">
                                <?php $syarat = json_decode($data->qualifikasi, true); ?>
                                <div class="form-group row">
                                    <label for="pendidikan-terakhir" class="col-form-label col-md-4 col-sm-4">
                                        Pendidikan Terakhir
                                    </label>
                                    <div class="col-md-8">
                                        <select name="last_education" id="pendidikan-terakhir" class="form-control select2">
                                            <option value="1">SD</option>
                                            <option value="2">SMP</option>
                                            <option value="3">SMA</option>
                                            <option value="4">D3</option>
                                            <option value="5">S1</option>
                                            <option value="6">S2</option>
                                            <option value="7">S3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="jurusan" class="col-form-label col-md-4 col-sm-4">
                                        Jurusan
                                    </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <?php if(isset($syarat['syarat_jurusan'])) : ?>
                                            <?php foreach(json_decode($syarat['syarat_jurusan']) as $jurusan) : ?>
                                            <div class="d-inline-block custom-control custom-radio mr-1">
                                                <input type="radio" name="jurusan" class="custom-control-input major" id="<?= $jurusan ?>" value="<?= $jurusan ?>">
                                                <label class="custom-control-label" for="<?= $jurusan ?>"><?= $jurusan ?></label>
                                            </div>
                                            <?php endforeach ?>
                                            <div class="d-inline-block custom-control custom-radio mr-1">
                                                <input type="radio" name="jurusan" class="custom-control-input major" id="other-major" value="other">
                                                <label class="custom-control-label" for="other-major">Jurusan Lain</label>
                                            </div>
                                            <?php else : ?>
                                                <input type="text" id="jurusan" class="form-control" name="jurusan">
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row jurusan-lain d-none">
                                    <div class="col-md-4"></div>
                                    <div class="form-group col-md-8 mb-2">
                                        <input type="text" placeholder="Jurusan Lain" id="jurusan-lain" class="form-control" name="jurusan_lain">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nilai-terakhir" class="col-form-label col-md-4 col-sm-4">
                                        Nilai Terakhir
                                    </label>
                                    <div class="col-md-8">
                                        <input type="number" name="nilai_terakhir" id="nilai-terakhir" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-sm-4">
                                        Pengalaman Kerja
                                    </label>
                                    <div class="col-md-8">
                                        <div class="input-group" style="margin-top: 10px">
                                            <div class="d-inline-block custom-control custom-radio mr-1">
                                                <input type="radio" name="berpengalaman" class="custom-control-input experience" id="yes" value="yes">
                                                <label class="custom-control-label" for="yes">Berpengalaman</label>
                                            </div>
                                            <div class="d-inline-block custom-control custom-radio">
                                                <input type="radio" name="berpengalaman" class="custom-control-input experience" id="no" value="no">
                                                <label class="custom-control-label" for="no">Fresh Graduate</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row experience-year d-none">
                                    <label class="col-form-label col-md-4 col-sm-4">
                                    </label>
                                    <div class="col-md-8">
                                        <input type="number" placeholder="Lama Pengalaman (Tahun)" id="experience-time" class="form-control" name="lama_pengalaman">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" name="submit" class="btn btn-info text-white btn-block submit">
                                            <?php echo lang('JobVacancies.submit') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<link href="<?php echo base_url('vendors/bootstrap-fileinput/bootstrap-fileinput.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('vendors/multiselect/bootstrap-multiselect.min.css'); ?>" rel="stylesheet" type="text/css" />
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<script src="<?php echo base_url('vendors/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/jquery.form.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/bootstrap-fileinput/bootstrap-fileinput.js'); ?>"></script>
<script src="<?php echo base_url('vendors/multiselect/bootstrap-multiselect.min.js'); ?>" type="text/javascript"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<script src="<?php echo base_url('js/form.js'); ?>"></script>
<script src="<?php echo base_url('js/modules/apply.js'); ?>"></script>
<?php $this->endSection(); ?>
