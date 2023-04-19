<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-none">
            <div class="card-header border-bottom">
                <h4 class="font-weight-bold text-secondary mb-0">Lengkapi Data</h4>
            </div>
            <div class="card-body">
                <form id="complete-data" method="post" enctype="multipart/form-data">
                    <div class="row justify-content-md-center">
                        <div class="col-md-6">
                            <div class="form-body">
                                <?php if(in_groups('recruiter')) : ?>
                                    <?php if(
                                        !$user->ktp || 
                                        !$user->file_vaksin_1 || 
                                        !$user->tanggal_vaksin_1 ||
                                        !$user->file_vaksin_2 || 
                                        !$user->tanggal_vaksin_2 || 
                                        !$user->file_vaksin_3 ||
                                        !$user->tanggal_vaksin_3
                                    ) : ?>
                                        <div class="row">
                                            <div class="form-group col-12 mb-2">
                                                <label for="ktp">Foto KTP</label><br>
                                                <canvas id="preview-0" style="height:0px"></canvas>
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                                    <div>
                                                        <span class="btn btn-raised btn-success btn-file">
                                                            <span class="fileinput-new"><?= lang('Common.btn.image_picker.select') ?></span>
                                                            <span class="fileinput-exists"><?= lang('Common.btn.image_picker.change') ?></span>
                                                            <input class="upload-file" type="file" name="ktp" data-param="0" accept="image/*, application/pdf">
                                                        </span>
                                                        <input type="hidden" name="delete_image" value="">
                                                        <a href="#" class="btn btn-raised btn-danger fileinput-exists delete_image" data-dismiss="fileinput"><?= lang('Common.btn.image_picker.delete') ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-12 mb-2">
                                                <label for="file-vaksin-1">File Vaksin 1</label><br>
                                                <canvas id="preview-1" style="height:0px"></canvas>
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                                    <div>
                                                        <span class="btn btn-raised btn-warning btn-file">
                                                            <span class="fileinput-new"><?= lang('Common.btn.image_picker.pdf') ?></span>
                                                            <span class="fileinput-exists"><?= lang('Common.btn.image_picker.change') ?></span>
                                                            <input class="upload-file" type="file" name="file_vaksin_1" data-param="1" accept="image/*, application/pdf">
                                                        </span>
                                                        <input type="hidden" name="delete_image" value="">
                                                        <a href="#" class="btn btn-raised btn-danger fileinput-exists delete_image" data-dismiss="fileinput"><?= lang('Common.btn.image_picker.delete') ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-12 mb-2">
                                                <label for="vaccin-date-1">Tanggal Vaksin 1</label>
                                                <input type="date" id="vaccin-date-1" class="form-control" name="tanggal_vaksin_1">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-12 mb-2">
                                                <label for="file-vaksin-2">File Vaksin 2</label><br>
                                                <canvas id="preview-2" style="height:0px"></canvas>
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                                    <div>
                                                        <span class="btn btn-raised btn-warning btn-file">
                                                            <span class="fileinput-new"><?= lang('Common.btn.image_picker.pdf') ?></span>
                                                            <span class="fileinput-exists"><?= lang('Common.btn.image_picker.change') ?></span>
                                                            <input class="upload-file" type="file" name="file_vaksin_2" data-param="2" accept="image/*, application/pdf">
                                                        </span>
                                                        <input type="hidden" name="delete_image" value="">
                                                        <a href="#" class="btn btn-raised btn-danger fileinput-exists delete_image" data-dismiss="fileinput"><?= lang('Common.btn.image_picker.delete') ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-12 mb-2">
                                                <label for="vaccin-date-2">Tanggal Vaksin 2</label>
                                                <input type="date" id="vaccin-date-2" class="form-control" name="tanggal_vaksin_2">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-12 mb-2">
                                                <label for="file-vaksin-3">File Vaksin 3</label><br>
                                                <canvas id="preview-3" style="height:0px"></canvas>
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                                    <div>
                                                        <span class="btn btn-raised btn-warning btn-file">
                                                            <span class="fileinput-new"><?= lang('Common.btn.image_picker.pdf') ?></span>
                                                            <span class="fileinput-exists"><?= lang('Common.btn.image_picker.change') ?></span>
                                                            <input class="upload-file" type="file" name="file_vaksin_3" data-param="3" accept="image/*, application/pdf">
                                                        </span>
                                                        <input type="hidden" name="delete_image" value="">
                                                        <a href="#" class="btn btn-raised btn-danger fileinput-exists delete_image" data-dismiss="fileinput"><?= lang('Common.btn.image_picker.delete') ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-12 mb-2">
                                                <label for="vaccin-date-3">Tanggal Vaksin 3</label>
                                                <input type="date" id="vaccin-date-3" class="form-control" name="tanggal_vaksin_3">
                                            </div>
                                        </div>

                                        <hr>
                                    <?php endif ?>
                                <?php endif ?>

                                <div class="row">
                                    <div class="form-group col-12 mb-2">
                                        <label for="pass-photo">Pass Foto Terbaru</label>
                                        <br>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                            <div>
                                                <span class="btn btn-raised btn-success btn-file">
                                                    <span class="fileinput-new"><?= lang('Common.btn.image_picker.select') ?></span>
                                                    <span class="fileinput-exists"><?= lang('Common.btn.image_picker.change') ?></span>
                                                    <input type="file" name="pas_photo" accept="image/*">
                                                </span>
                                                <input type="hidden" name="delete_image" value="">
                                                <a href="#" class="btn btn-raised btn-danger fileinput-exists delete_image" data-dismiss="fileinput"><?= lang('Common.btn.image_picker.delete') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-12 mb-2">
                                        <label for="cv">CV Terbaru</label><br>
                                        <canvas id="preview-4" style="height:0px"></canvas>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                            <div>
                                                <span class="btn btn-raised btn-warning btn-file">
                                                    <span class="fileinput-new"><?= lang('Common.btn.image_picker.pdf') ?></span>
                                                    <span class="fileinput-exists"><?= lang('Common.btn.image_picker.change') ?></span>
                                                    <input class="upload-file" type="file" name="cv" data-param="4" accept="application/pdf">
                                                </span>
                                                <input type="hidden" name="delete_image" value="">
                                                <a href="#" class="btn btn-raised btn-danger fileinput-exists delete_image" data-dismiss="fileinput"><?= lang('Common.btn.image_picker.delete') ?></a>
                                            </div>
                                        </div>
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
<script src="<?php echo base_url('js/modules/complete_data.js'); ?>"></script>
<?php $this->endSection(); ?>
