<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<div class="d-flex h-100 card pb-5 mb-5 px-2" style="border-radius: 0">
    <div class="page-header d-flex justify-content-between flex-wrap p-2">
        <div class="d-flex align-items-center">
            <h4 class="font-weight-bold"><?php echo (isset($heading) ? $heading : lang('heading')); ?></h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
        </div>
    </div>
    <form id="form" action="<?= route_to('job_vacancy_save') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data && $data->id ? $data->id : '' ?>">
        <div class="form-group row">
            <label for="posisi" class="col-form-label col-md-2 col-sm-4">
                Posisi
            </label>
            <div class="col-md-5">
                <input type="text" name="posisi" value="<?= $data && $data->posisi? $data->posisi : '' ?>" id="posisi" required="required" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="deskripsi" class="col-form-label col-md-2 col-sm-4">
                Deskripsi
            </label>
            <div class="col-md-8">
                <textarea name="deskripsi" id="deskripsi" required="required"  class="form-control editor"><?= $data && $data->deskripsi ? $data->deskripsi : '' ?></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="batas-tanggal" class="col-form-label col-md-2 col-sm-4">
                Batas Tanggal
            </label>
            <div class="col-md-5">
                <input type="date" name="batas_tanggal" required="required" value="<?= $data && $data->batas_tanggal ? $data->batas_tanggal : '' ?>" id="batas-tanggal" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="gambar" class="col-form-label col-md-2 col-sm-4">
                Gambar
            </label>
            <div class="col-md-5 col-sm-8">
                <div class="fileinput <?= $data && $data->gambar ? 'fileinput-exists' : 'fileinput-new' ?>" data-provides="fileinput">
                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 200px;">
                        <?php if($data && $data->gambar) : ?>
                        <?php
                            $cld = new \Cloudinary\Cloudinary(CLD_CONFIG);
                            $image = $cld->image($data->gambar);
                        ?>
                            <img style="width:200px; height:200px; object-fit: cover" src="<?= $image ?>">
                        <?php endif ?>
                    </div>
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
        <?php
            $qualifikasi = $data && $data->qualifikasi ? json_decode($data->qualifikasi) : NULL;

            $syarat_umur = NULL;
            $syarat_gender = NULL;
            $syarat_jurusan = NULL;
            $minimum_nilai = NULL;
            $minimum_pengalaman = NULL;
            $berpengalaman = false;
            
            if($qualifikasi){
                $syarat_umur = isset($qualifikasi->syarat_umur) ? $qualifikasi->syarat_umur : NULL;
                $syarat_gender = isset($qualifikasi->syarat_gender) ? $qualifikasi->syarat_gender : NULL;
                $syarat_jurusan = isset($qualifikasi->syarat_jurusan) ? implode(',', json_decode($qualifikasi->syarat_jurusan, true)) : NULL;
                $minimum_nilai = isset($qualifikasi->minimum_nilai) ? $qualifikasi->minimum_nilai : NULL;
                $minimum_pengalaman = isset($qualifikasi->minimum_pengalaman) ? $qualifikasi->minimum_pengalaman : NULL;
                $berpengalaman = isset($qualifikasi->berpengalaman) ? $qualifikasi->berpengalaman : false;
            };
        ?>
        <div class="form-group row">
            <label for="syarat-umur" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input syarat-umur" <?= $qualifikasi && $syarat_umur ? 'checked' : '' ?> name="syarat_umur" value="1" id="age-qualification">
                    <label class="custom-control-label" for="age-qualification">Syarat Umur</label>
                  </div>
            </div>
        </div>
        <div class="form-group row umur <?= $qualifikasi && $syarat_umur ? '' : 'd-none' ?>">
            <label for="age" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <input type="number" class="form-control" name="age" value="<?= $qualifikasi && $syarat_umur ? $syarat_umur : '' ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="syarat-gender" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input syarat-gender" <?= $qualifikasi && $syarat_gender ? 'checked' : '' ?> name="syarat_gender" value="1" id="gender-qualification">
                    <label class="custom-control-label" for="gender-qualification">Syarat Gender</label>
                  </div>
            </div>
        </div>
        <div class="form-group row gender <?= $qualifikasi && $syarat_gender ? '' : 'd-none' ?>">
            <label for="gender" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input" <?= !$qualifikasi ? 'checked' : ( $qualifikasi && $syarat_gender == 'laki-laki' ? 'checked' : '') ?> value="laki-laki" name="gender" id="gender-1">
                    <label class="custom-control-label" for="gender-1">Laki-Laki</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input" <?= $qualifikasi && $syarat_gender == 'perempuan' ? 'checked' : '' ?> value="perempuan" name="gender" id="gender-2">
                    <label class="custom-control-label" for="gender-2">Perempuan</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="pendidikan-terakhir" class="col-form-label col-md-2 col-sm-4">
                Pendidikan Terakhir
            </label>
            <div class="col-md-5">
                <select name="last_education" id="pendidikan-terakhir" required="required" class="form-control select2">
                    <option value="1" <?= $qualifikasi && $qualifikasi->last_education == 1 ? 'selected' : '' ?>>SD</option>
                    <option value="2" <?= $qualifikasi && $qualifikasi->last_education == 2 ? 'selected' : '' ?>>SMP / MTs</option>
                    <option value="3" <?= $qualifikasi && $qualifikasi->last_education == 3 ? 'selected' : '' ?>>SMA / SMK</option>
                    <option value="4" <?= $qualifikasi && $qualifikasi->last_education == 4 ? 'selected' : '' ?>>D3</option>
                    <option value="5" <?= $qualifikasi && $qualifikasi->last_education == 5 ? 'selected' : '' ?>>S1</option>
                    <option value="6" <?= $qualifikasi && $qualifikasi->last_education == 6 ? 'selected' : '' ?>>S2</option>
                    <option value="7" <?= $qualifikasi && $qualifikasi->last_education == 7 ? 'selected' : '' ?>>S3</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 col-sm-4">
                Syarat Jurusan
            </label>
            <div class="col-md-5">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input syarat-jurusan" name="syarat_jurusan" value="semua_jurusan" id="syarat-jurusan-1" <?= !$qualifikasi || !$syarat_jurusan ? 'checked' : ''?>>
                    <label class="custom-control-label" for="syarat-jurusan-1">Semua Jurusan</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input syarat-jurusan" name="syarat_jurusan" value="jurusan_spesifik" id="syarat-jurusan-2" <?= $qualifikasi && $syarat_jurusan ? 'checked' : ''?>>
                    <label class="custom-control-label" for="syarat-jurusan-2">Jurusan Spesifik</label>
                </div>
            </div>
        </div>
        <div class="form-group row list-jurusan <?= !$qualifikasi || !$syarat_jurusan ? 'd-none' : ''?>">
            <label for="jurusan" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <input type="text" placeholder="Tambah Jurusan" name="jurusan" value="<?= $qualifikasi && $syarat_jurusan ? $syarat_jurusan : ''?>" id="jurusan" class="form-control tag">
                <small class="text-muted block-area">Pisah Jurusan dengan menekan enter</small>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 col-sm-4">
                Nilai Minimum
            </label>
            <div class="col-md-5">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input minimum-nilai" value="tidak" name="minimum_nilai" id="nilai-minimum-1" <?= !$qualifikasi || !$minimum_nilai ? 'checked' : ''?>>
                    <label class="custom-control-label" for="nilai-minimum-1">Tidak ada nilai minimum</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input minimum-nilai" value="ya" name="minimum_nilai" id="nilai-minimum-2" <?= $qualifikasi && $minimum_nilai ? 'checked' : ''?>>
                    <label class="custom-control-label" for="nilai-minimum-2">Syarat Nilai Minimum</label>
                </div>
            </div>
        </div>
        <div class="form-group row nilai  <?= !$qualifikasi || !$minimum_nilai ? 'd-none' : ''?>">
            <label for="syarat-nilai" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <input type="number" placeholder="Syarat Nilai" name="syarat_nilai" value="<?= $qualifikasi && $minimum_nilai ? $minimum_nilai : ''?>" id="syarat-nilai" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 col-sm-4">
                Kriteria
            </label>
            <div class="col-md-5">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input kriteria" name="kriteria" value="Fresh Graduate" id="kriteria-1" <?= !$qualifikasi || !$berpengalaman ? 'checked' : ''?>>
                    <label class="custom-control-label" for="kriteria-1">Fresh Graduate & Berpengalaman</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" class="custom-control-input kriteria" name="kriteria" value="Berpengalaman" id="kriteria-2" <?= $qualifikasi && $berpengalaman ? 'checked' : ''?>>
                    <label class="custom-control-label" for="kriteria-2">Berpengalaman Saja</label>
                </div>
            </div>
        </div>
        <div class="form-group row berpengalaman <?= !$qualifikasi || !$berpengalaman ? 'd-none' : ''?>">
            <label for="lama-pengalaman" class="col-form-label col-md-2 col-sm-4"></label>
            <div class="col-md-5">
                <input type="number" placeholder="Minimum Pengalaman" name="minimum_pengalaman" value="<?= $qualifikasi && $minimum_pengalaman ? $minimum_pengalaman : ''?>" id="lama-pengalaman" class="form-control">
                <small class="text-muted block-area">Dalam Tahun</small>
            </div>
        </div>
        <input type="hidden" name="id_psikotest" value="<?= $psikotest && $psikotest->id ?  $psikotest->id : '' ?>">
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
                <select required="required" name="kategori_soal[]" multiple="true" id="kategori-soal" class="form-control select2">
                    <!-- <?php //foreach($kategori_soal as $kategori) : ?>
                    <option value="<?php //echo $kategori->id ?>"><?php //echo $kategori->kategori ?></option>
                    <?php //endforeach ?> -->
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="waktu-pengerjaan" class="col-form-label col-md-2 col-sm-4">
                Waktu Pengerjaan
            </label>
            <div class="col-md-5">
                <input type="number" name="waktu_pengerjaan" required="required" value="<?= $psikotest && $psikotest->waktu_pengerjaan ?  $psikotest->waktu_pengerjaan : '' ?>" id="waktu-pengerjaan" class="form-control">
                <small class="text-muted block-area">Menit</small>
            </div>
        </div>
        <div class="form-group row">
            <label for="nilai-persoal" class="col-form-label col-md-2 col-sm-4">
                Nilai Per-Soal
            </label>
            <div class="col-md-5">
                <input type="number" required="required" name="nilai_persoal" value="<?= $psikotest && $psikotest->point_persoal ?  $psikotest->point_persoal : '' ?>" id="nilai-persoal" class="form-control">
                <div id="jumlah-soal-psikotest">
                    <?php if(isset($jumlah_soal)) : ?>
                        <small class="text-muted block-area">Jumlah Soal : <?= $jumlah_soal ?> Soal</small>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="nilai-minimum" class="col-form-label col-md-2 col-sm-4">
                Nilai Minimum
            </label>
            <div class="col-md-5">
                <input type="number" name="nilai_minimum" <?= !$psikotest || !$psikotest->point_persoal ?  'disabled="disabled"' : '' ?> required="required" value="<?= $psikotest && $psikotest->nilai_minimum ?  $psikotest->nilai_minimum : '' ?>" id="nilai-minimum" class="form-control">
                <div id="total-nilai">
                    <?php if(isset($total_nilai)) : ?>
                        <small class="text-muted block-area">Total Nilai : <?= $total_nilai ?>. Nilai Minimum tidak boleh melebihi nilai ini. </small>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_interview" value="<?= $data && $data->id_interview ? $data->id_interview : '' ?>">
        <div class="form-group mt-1">
            <input type="checkbox" id="switcherySize2" name="set_interview" class="switchery set-interview" data-size="sm" value="1" <?= $data && $data->id_interview ? 'checked' : '' ?>/>
            <label for="switcherySize2" class="font-medium-2 text-bold-600 ml-1">Atur Jadwal Interview</label>
        </div>
        <div class="row interview <?= $data && $data->id_interview ? '' : 'd-none' ?>">
            <div class="col-12">
                <div class="form-group row">
                    <label for="agenda" class="col-form-label col-md-2 col-sm-4">
                        Agenda
                    </label>
                    <div class="col-md-5">
                        <input type="text" name="agenda" value="<?= $interview && $interview->agenda ? $interview->agenda : '' ?>" id="agenda" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tanggal-interview" class="col-form-label col-md-2 col-sm-4">
                        Tanggal Interview
                    </label>
                    <div class="col-md-5">
                        <input type="date" name="tanggal" value="<?= $interview && $interview->tanggal ? $interview->tanggal : '' ?>" id="tanggal-interview" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="waktu-interview" class="col-form-label col-md-2 col-sm-4">
                        Waktu
                    </label>
                    <div class="col-md-5">
                        <input type="time" name="waktu" value="<?= $interview && $interview->waktu ? $interview->waktu : '' ?>" id="waktu-interview" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pewawancara" class="col-form-label col-md-2 col-sm-4">
                        Pewawancara
                    </label>
                    <div class="col-md-5">
                        <input type="text" name="pewawancara" value="<?= $interview && $interview->pewawancara ? $interview->pewawancara : '' ?>" id="pewawancara" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-sm-4"></label>
                    <div class="col-md-5">
                        <div class="d-inline-block custom-control custom-checkbox mr-1">
                            <input type="checkbox" class="custom-control-input is-online" name="is_online" <?= $interview && $interview->via == 'online' ? 'checked' : '' ?> value="1" id="is-online">
                            <label class="custom-control-label" for="is-online">Interview Online</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row <?= $interview && $interview->via == 'online' ? '' : 'd-none' ?> link">
                    <label for="link" class="col-form-label col-md-2 col-sm-4">
                        Link
                    </label>
                    <div class="col-md-5">
                        <input type="text" name="link" value="<?= $interview && $interview->link ? $interview->link : '' ?>" id="link" class="form-control">
                    </div>
                </div>
                <div class="form-group row lokasi <?= $interview && $interview->via == 'online' ? 'd-none' : '' ?>">
                    <label for="tempat" class="col-form-label col-md-2 col-sm-4">
                        Lokasi
                    </label>
                    <div class="col-md-8">
                        <textarea name="tempat" id="tempat" class="form-control" cols="10" rows="4"><?= $interview && $interview->tempat ? $interview->tempat : '' ?></textarea>
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
<script>
    let point_persoal = parseInt('<?= $psikotest && $psikotest->point_persoal ? $psikotest->point_persoal : 0 ?>');
    let total_soal = parseInt('<?= isset($jumlah_soal) ? $jumlah_soal : 0 ?>');
    let total_nilai = parseInt('<?= isset($total_nilai) ? $total_nilai : 0 ?>');

    let categories = '<?= $kategori_soal ? json_encode($kategori_soal) : 'NULL' ?>';
    let id_kategori = '<?= $psikotest && $psikotest->kategori_soal_ids ? $psikotest->kategori_soal_ids : NULL ?>';
    let data = [];
    if(categories){
        categories = JSON.parse(categories);
        categories.forEach((d, i) => {
            data.push({id: d.id_kategori, text: d.kategori })
        })
    }

    $("#kategori-soal").select2({
        data: data
    })

    if(id_kategori){
        $("#kategori-soal").val(JSON.parse(id_kategori));
        $("#kategori-soal").trigger("change");
    }

</script>
<script src="<?php echo base_url('js/form.js'); ?>"></script>
<script src="<?php echo base_url('js/form_vacancy.js'); ?>"></script>
<?php if (isset($customJS)) {
    foreach ($customJS as $file) {
        echo '<script src="' . $file . '?v=' . $_ENV['ASSETV'] . '"></script>';
    }
} ?>

<?php $this->endSection(); ?>