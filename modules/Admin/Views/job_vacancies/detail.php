<?php $this->extend('layout/default'); ?>
<?php $this->section('content'); ?>
<?php use CodeIgniter\I18n\Time; ?>
<div class="content-header-left col-md-6 col-12 my-2 breadcrumb-new">
    <h2 class="content-header-title mb-0 d-inline-block"><?php echo $heading ?></h2>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
        </div>
    </div>
</div>
<div class="d-flex h-100 card pb-5 mb-5 px-2">
    <div class="card-body">
        <div id="accordionWrap4" role="tablist" aria-multiselectable="true">
            <div class="card accordion collapse-icon accordion-icon-rotate">
                <a id="heading41" class="card-header bg-success success" data-toggle="collapse" href="#accordion-detail" aria-expanded="true" aria-controls="accordion-detail">
                    <div class="card-title lead white"><?= $detail->posisi ?></div>
                </a>
                <div id="accordion-detail" role="tabpanel" data-parent="#accordionWrap4" aria-labelledby="heading41" class="border-success card-collapse collapse" aria-expanded="true">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="font-weight-bold text-muted"><?= lang('JobVacancies.description') ?></h5>
                                <?= $detail->deskripsi ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs nav-top-border no-hover-bg nav-justified">
            <li class="nav-item">
                <a href="#tab-applicant" class="nav-link <?= !session()->getFlashData('open_interview_tab') ? 'show active' : '' ?>" data-toggle="tab"> <i class="ft-users"></i> <?php echo lang('JobVacancies.applicant'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#tab-interview" class="nav-link <?= session()->getFlashData('open_interview_tab') ? 'show active' : '' ?>" data-toggle="tab"> <i class="la la-commenting"></i> <?php echo lang('Common.interview'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#tab-account" class="nav-link" data-toggle="tab"> <i class="ft-lock"></i> <?php echo lang('Applicants.account'); ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade <?= !session()->getFlashData('open_interview_tab') ? 'show active' : '' ?> p-2" id="tab-applicant">
            
            <table class="table table-striped" id="table-applicants" data-pagination="true"
                   data-search="true" data-page-list="[10, 25, 50, 100, 500]"
                   data-sticky-header="true">
                <thead>
                    <tr>
                        <th data-field="nama_lengkap"><?php echo lang('Applicants.fullname'); ?></th>
                        <th data-field="pas_photo" data-formatter="imageFormatterDefault"><?php echo lang('Applicants.photo'); ?></th>
                        <th data-field="cv" data-formatter="cvFormatterDefault"><?php echo lang('Applicants.cv'); ?></th>
                        <th data-field="status" data-formatter="statusFormatterDefault"><?php echo lang('JobApplications.status'); ?></th>
                    </tr>
                </thead>
            </table>
            </div>
            <div class="tab-pane <?= session()->getFlashData('open_interview_tab') ? 'show active' : '' ?> fade p-2" id="tab-interview">
                <h5 class="font-weight-bold text-muted">
                    <i class="la la-calendar"></i>
                    Jadwal Interview
                </h5>
                <div class="card shadow-none border mb-5">
                    <div class="card-body">
                        <form id="interview-schedule">
                            <input type="hidden" name="id_lowongan" value="<?= $detail->id ?>">
                            <input type="hidden" name="id_interview" value="<?= $interview_data ? $interview_data->id : ''  ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control">Agenda</label>
                                        <div class="col-md-9 mx-auto">
                                            <input class="form-control" type="text" name="agenda" value="<?= $interview_data ? $interview_data->agenda : ''  ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control">Tanggal</label>
                                        <div class="col-md-9 mx-auto">
                                            <input class="form-control" type="date" name="tanggal" value="<?= $interview_data ? $interview_data->tanggal : ''  ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control">Waktu</label>
                                        <div class="col-md-9 mx-auto">
                                            <input class="form-control" type="time" name="waktu" value="<?= $interview_data ? $interview_data->waktu : ''  ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control">Interviewer</label>
                                        <div class="col-md-9 mx-auto">
                                            <input class="form-control" type="text" name="pewawancara" value="<?= $interview_data ? $interview_data->pewawancara : ''  ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="checkbox" id="switcherySize2" name="is_online" class="switchery is-online" data-size="sm" value="1" <?= $interview_data && $interview_data->via == 'online' ? 'checked' : '' ?>/>
                                        <label for="switcherySize2" class="font-medium-2 text-bold-600 ml-1">Interview Online</label>
                                    </div>
                                    <div class="form-group row <?= $interview_data && $interview_data->via == 'online' ? '' : 'd-none' ?> link">
                                        <label class="col-md-3 label-control">Link</label>
                                        <div class="col-md-9 mx-auto">
                                            <input class="form-control" type="text" name="link" value="<?= $interview_data ? $interview_data->link : ''  ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row lokasi <?= $interview_data && $interview_data->via == 'online' ? 'd-none' : '' ?>">
                                        <label class="col-md-3 label-control" for="tempat">Tempat</label>
                                        <div class="col-md-9 mx-auto">
                                            <textarea id="tempat" rows="6" class="form-control" name="tempat"><?= $interview_data ? $interview_data->tempat : ''  ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-warning mr-1 send">Edit & Kirim Email</button>
                                    <button type="button" class="btn btn-primary save">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <h5 class="font-weight-bold text-muted">
                    <i class="la la-pencil"></i>
                    Input Nilai Interview
                </h5>
                <div class="card shadow-none border">
                    <div class="card-body">
                        <?php if($interviews) : ?>
                            <form id="form" action="<?= route_to('save_interview') ?>" method="post">
                                <input type="hidden" name="id_lowongan" value="<?= $detail->id ?>">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th class="border-0">Nama Lengkap</th>
                                            <th class="border-0">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($interviews as $interview) : ?>
                                        <tr>
                                            <td style="vertical-align:middle"><?= $interview->nama_lengkap ?></td>
                                            <td> <input type="number" name="nilai_interview[<?= $interview->id_pelamar ?>]" class="form-control" value="<?= $interview->nilai_interview ? $interview->nilai_interview : 0 ?>"> </td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button type="button" name="submit" class="btn btn-info text-white btn-block submit">Simpan Nilai</button>
                                    </div>
                                </div>
                            </form>
                        <?php else : ?>
                            <div class="col-12 text-center">
                                <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade p-2" id="tab-account">
                
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.css">
<link href="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/sticky-header/bootstrap-table-sticky-header.css" rel="stylesheet">
<link href="<?php echo base_url('vendors/css/forms/toggle/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('vendors/css/forms/toggle/switchery.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('css/plugins/forms/switch.min.css'); ?>" rel="stylesheet" type="text/css" />
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<style>
    .border{
        border: 1px solid #e5e5e5!important;
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/cookie/bootstrap-table-cookie.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>
<script src="<?php echo base_url('vendors/js/forms/toggle/switchery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/scripts/forms/switchery.min.js'); ?>" type="text/javascript"></script>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<script src="<?php echo base_url('js/jquery.cloudinary.js'); ?>"></script>
<script>
    var applicants = <?php echo json_encode($applicants); ?>;
    
    $('#table-applicants').bootstrapTable({
        data: applicants
    });

    function imageFormatterDefault(value, row, index) {
        let $image = "";
        if (row.pas_photo) {
            const img = $.cloudinary.url(row.pas_photo, {
                        height: 50,
                        width: 50,
                        crop: "fill",
                        cloud_name: "anas27",
                        secure: true
                    });
            $image = '<img src="' + img + '" class="rounded"/>';
        }
        return [$image].join("");
    }

    function cvFormatterDefault(value, row, index) {
        let $cv = "";
        if (row.cv) {
            const url = $.cloudinary.url(row.cv + '.png', {
                        cloud_name: "anas27",
                        secure: true
                    });
            $cv = '<a class="btn btn-primary btn-sm" href="' + url.replace('v1', 'fl_attachment') + '" download><i class="ft-download"></i> Download </a>';
        }
        return [$cv].join("");
    }

    function statusFormatterDefault(value, row, index, field) {
        let $status = "";
        if (value) {
            $status +=
            '<span class="badge badge-pill status-label status-' + value + '">' 
                + value +
            "</span>";
        }
        return [$status].join("");
    }

    $('.submit').on('click', function(){
        let url = $('#form').attr('action');
        $(this).attr("disabled", "disabled");
        $(this).html(loadingButtonText);

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: $('#form').serializeArray(),
            success: function(res){
                if (res.status === "success") {
                    window.location = res.redirect;
                } else {
                    $(this).removeAttr("disabled");
                    $(this).html("Simpan Nilai");
                    errorMessage(res.message)
                }
            }
        })
    })

    $('.save').on('click', function(){
        let $save = $(this);
        $save.attr("disabled", "disabled");
        $save.html(loadingButtonText);

        $.ajax({
            type: 'post',
            url: siteUrl + '<?= route_to('save_schedule') ?>',
            dataType: 'json',
            data: $('#interview-schedule').serializeArray(),
            success: function(res){
                console.log(res)
                if (res.status === "success") {
                    window.location = res.redirect;
                } else {
                    $save.removeAttr("disabled");
                    $save.html("Simpan");
                    errorMessage(res.message)
                }
            }
        })
    })

    $('.send').on('click', function(){

        $(this).attr("disabled", "disabled");
        $(this).html(loadingButtonText);

        $.ajax({
            type: 'post',
            url: siteUrl + '<?= route_to('save_and_send') ?>',
            dataType: 'json',
            data: $('#interview-schedule').serializeArray(),
            success: function(res){
                if (res.status === "success") {
                    window.location = res.redirect;
                } else {
                    $(this).removeAttr("disabled");
                    $(this).html("Simpan");
                    errorMessage(res.message)
                }
            }
        })
    })

    $("body").on("click", ".switchery", function () {
        if ($(".is-online").is(":checked")) {
            $(".link").removeClass("d-none");
            $(".lokasi").addClass("d-none");
        } else {
            $(".lokasi").removeClass("d-none");
            $(".link").addClass("d-none");
        }
    });
</script>
<?php $this->endSection(); ?>