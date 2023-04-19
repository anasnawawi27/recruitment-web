<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<div class="d-flex h-100 card pb-5 mb-5 px-2" style="border-radius: 0">
    <div class="page-header d-flex justify-content-between flex-wrap p-2">
        <div class="d-flex align-items-center">
            <h4 class="font-weight-bold"><?php echo (isset($heading) ? $heading : lang('heading')); ?></h4>
        </div>
        <?php if (!isset($form['is_form_report'])) { ?>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
            </div>
        <?php } ?>
    </div>
    <form id="form" action="<?= route_to('job_vacancy_save') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="id_lowongan" value="">
        <div class="form-group row">
            <label for="posisi" class="col-form-label col-md-2 col-sm-4">
                Posisi
            </label>
            <div class="col-md-5">
                <input type="text" name="posisi" value="" id="posisi" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="deskripsi" class="col-form-label col-md-2 col-sm-4">
                Deskripsi
            </label>
            <div class="col-md-5">
                <textarea name="deskripsi" value="" id="deskripsi"  class="form-control editor"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="batas-tanggal" class="col-form-label col-md-2 col-sm-4">
                Batas Tanggal
            </label>
            <div class="col-md-5">
                <input type="date" name="batas_tanggal" value="" id="batas-tanggal" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="gambar" class="col-form-label col-md-2 col-sm-4">
                Gambar
            </label>
            <div class="col-md-5 col-sm-8">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 200px;"></div>
                    <div>
                        <span class="btn btn-raised btn-success btn-file">
                            <span class="fileinput-new">
                                <i class="mdi mdi-upload"></i> Select Image
                            </span>
                            <span class="fileinput-exists">
                                <i class="mdi mdi-upload"></i> Change
                            </span>
                            <input type="file" name="upload_image" accept="image/*">
                        </span>
                        <input type="hidden" name="delete_image" value="">
                        <a href="#" class="btn btn-raised btn-danger fileinput-exists delete_image" data-dismiss="fileinput">
                            <i class="mdi mdi-remove"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col border-bottom">
                <h4 class="font-weight-bolder">Kualifikasi</h4>
            </div>
        </div>
        <div class="form-group row">
            <label for="pendidikan-terakhir" class="col-form-label col-md-2 col-sm-4">
                Pendidikan Terakhir
            </label>
            <div class="col-md-5">
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
            <label class="col-form-label col-md-2 col-sm-4">
                Syarat Jurusan
            </label>
            <div class="col-md-5">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input syarat-jurusan" name="syarat_jurusan" value="semua_jurusan" id="syarat-jurusan-1" checked>
                    <label class="custom-control-label" for="syarat-jurusan-1">Semua Jurusan</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input syarat-jurusan" name="syarat_jurusan" value="jurusan_spesifik" id="syarat-jurusan-2">
                    <label class="custom-control-label" for="syarat-jurusan-2">Jurusan Spesifik</label>
                </div>
            </div>
        </div>
        <div class="form-group row list-jurusan d-none">
            <label for="jurusan" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <input type="text" placeholder="Tambah Jurusan" name="jurusan" value="" id="jurusan" class="form-control tag">
                <small class="text-muted block-area">Pisah Jurusan dengan menekan enter</small>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 col-sm-4">
                Nilai Minimum
            </label>
            <div class="col-md-5">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input minimum-nilai" value="tidak" name="minimum_nilai" id="nilai-minimum-1" checked>
                    <label class="custom-control-label" for="nilai-minimum-1">Tidak ada nilai minimum</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input minimum-nilai" value="ya" name="minimum_nilai" id="nilai-minimum-2">
                    <label class="custom-control-label" for="nilai-minimum-2">Syarat Nilai Minimum</label>
                </div>
            </div>
        </div>
        <div class="form-group row nilai d-none">
            <label for="syarat-nilai" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <input type="number" placeholder="Syarat Nilai" name="syarat_nilai" value="" id="syarat-nilai" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 col-sm-4">
                Kriteria
            </label>
            <div class="col-md-5">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input" name="kriteria" value="Fresh Graduate" id="kriteria-1" checked>
                    <label class="custom-control-label" for="kriteria-1">Fresh Graduate & Berpengalaman</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input kriteria" name="kriteria" value="Berpengalaman" id="kriteria-2">
                    <label class="custom-control-label" for="kriteria-2">Berpengalaman Saja</label>
                </div>
            </div>
        </div>
        <div class="form-group row berpengalaman d-none">
            <label for="lama-pengalaman" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <input type="text" placeholder="Minimum Pengalaman" name="minimum_pengalaman" value="" id="lama-pengalaman" class="form-control">
                <small class="text-muted block-area">Dalam Tahun</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col border-bottom">
                <h4 class="font-weight-bolder">Psikotest</h4>
            </div>
        </div>
        <div class="form-group row">
            <label for="kategori-soal" class="col-form-label col-md-2 col-sm-4">
                Kategori Soal
            </label>
            <div class="col-md-5">
                <select name="kategori_soal[]" multiple="multiple" id="kategori-soal" class="form-control select2">
                    <?php foreach($kategori_soal as $kategori) : ?>
                    <option value="<?= $kategori->id ?>"><?= $kategori->kategori ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="waktu-pengerjaan" class="col-form-label col-md-2 col-sm-4">
                Waktu Pengerjaan
            </label>
            <div class="col-md-5">
                <input type="number" name="waktu_pengerjaan" value="" id="waktu-pengerjaan" class="form-control">
                <small class="text-muted block-area">Menit</small>
            </div>
        </div>
        <div class="form-group row">
            <label for="nilai-persoal" class="col-form-label col-md-2 col-sm-4">
                Nilai Per-Soal
            </label>
            <div class="col-md-5">
                <input type="number" name="nilai_persoal" value="" id="nilai-persoal" class="form-control">
                <!-- <small class="text-muted block-area">Menit</small> -->
            </div>
        </div>
        <div class="form-group row">
            <label for="nilai-minimum" class="col-form-label col-md-2 col-sm-4">
                Nilai Minimum
            </label>
            <div class="col-md-5">
                <input type="number" name="nilai_minimum" value="" id="nilai-minimum" class="form-control">
            </div>
        </div>
        <div class="form-group mt-1">
            <input type="checkbox" id="switcherySize2" name="set_interview" class="switchery set-interview" data-size="sm" value="1"/>
            <label for="switcherySize2" class="font-medium-2 text-bold-600 ml-1">Atur Jadwal Interview</label>
        </div>
        <div class="row interview d-none">
            <div class="col-12">
                <div class="form-group row">
                    <label for="waktu-interview" class="col-form-label col-md-2 col-sm-4">
                        Waktu Interview
                    </label>
                    <div class="col-md-5">
                        <input type="datetime-local" name="waktu_interview" value="" id="waktu-interview" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pewawancara" class="col-form-label col-md-2 col-sm-4">
                        Pewawancara
                    </label>
                    <div class="col-md-5">
                        <input type="text" name="pewawancara" value="" id="pewawancara" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kontent-email" class="col-form-label col-md-2 col-sm-4">
                        Body Email
                    </label>
                    <div class="col-md-5">
                        <small class="text-muted">Konten Email yang akan dikirim ke interviewee</small>
                        <textarea name="konten_email" value="" id="kontent-email" class="form-control editor"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row text-right">
            <label class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5 col-sm-8">
                <a href="<?= route_to('job_vacancies') ?>" class="btn btn-light" style="color:black">Cancel</a>
                <button type="submit" class=" btn btn-primary ml-1">
                    <i class="mdi mdi-save"></i> Save
                </button>
            </div>
        </div>
    </form>
</div>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<link href="<?php echo base_url('vendors/bootstrap-fileinput/bootstrap-fileinput.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('vendors/multiselect/bootstrap-multiselect.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('vendors/css/forms/toggle/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('vendors/css/forms/toggle/switchery.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/plugins/forms/switch.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/tagsinput.css'); ?>" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<?php if (isset($pluginCSS)) {
    foreach ($pluginCSS as $file) {
        echo '<link href="' . $file . '" rel="stylesheet" type="text/css">';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<?php if (isset($customCSS)) {
    foreach ($customCSS as $file) {
        echo '<link href="' . $file . '?v=' . $_ENV['ASSETV'] . '" rel="stylesheet" type="text/css">';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<script src="<?php echo base_url('vendors/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/jquery.form.min.js'); ?>"></script>
<script src="<?php echo base_url('vendors/bootstrap-fileinput/bootstrap-fileinput.js'); ?>"></script>
<script src="<?php echo base_url('vendors/multiselect/bootstrap-multiselect.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendors/js/forms/toggle/switchery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/scripts/forms/switchery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('js/tagsinput.js'); ?>"></script>
<?php if (isset($pluginJS)) {
    foreach ($pluginJS as $file) {
        echo '<script src="' . $file . '"></script>';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<script src="<?php echo base_url('js/form.js'); ?>"></script>
<script src="<?php echo base_url('js/form_vacancy.js'); ?>"></script>
<?php if (isset($customJS)) {
    foreach ($customJS as $file) {
        echo '<script src="' . $file . '?v=' . $_ENV['ASSETV'] . '"></script>';
    }
} ?>

<?php $this->endSection(); ?>