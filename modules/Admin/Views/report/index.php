<?php $this->extend('layout/default') ?>
<?php $this->section('content') ?>
<div class="d-flex h-100 card pt-3">
    <div class="page-header px-3">
        <form id="form-report" action="<?php echo route_to('report_pdf'); ?>" method="post">
            <div class="row border-bottom pb-3">
                <div class="col-3 pr-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                        <input id="range" type="text" name="date" class="form-control" style="width: 75%;" placeholder="<?php echo lang('Common.select_date') ?>" autocomplete="off">
                    </div>
                </div>
                <div class="col-3">
                    <select style="min-width: 100% !important;max-width: 236px !important;" name="lowongan_id" id="select2-lowongan" class="select2">
                        <option></option>
                        <?php foreach($vacancies as $vacancy) : ?>
                            <option value="<?= $vacancy->id ?>"><?= $vacancy->posisi ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-3 pl-0">
                    <div class="d-flex">
                        <button type="button" class="btn btn-primary mr-1 show">Tampilkan</button>
                        <button type="submit" class="btn btn-danger">Download PDF</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="p-0">
        <span class="report-title">
            <h4 class="m-1 font-weight-bolder my-2">Report Karyawan Diterima</h4>
        </span>
        <div id="result-section">
            <div class="col-12 text-center mb-3">
                <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
            </div>
        </div>
            
    </div>
</div>
<?php $this->endSection() ?>
<?php $this->section('plugin_css') ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="<?= base_url('js/scripts/select2/css/select2.min.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('css/plugins/loaders/loaders.min.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('css/core/colors/palette-loader.min.css') ?>" />
<?php $this->endSection() ?>
<?php $this->section('custom_css') ?>
<style>
    .center-cropped {
        object-fit:cover;
        object-position: center;
        height: 50px;
        width: 50px;
        border-radius: 100%;
    }

</style>
<?php $this->endSection() ?>
<?php $this->section('plugin_js') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>  
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="<?php echo base_url('js/jquery.cloudinary.js'); ?>"></script>
<?php $this->endSection() ?>
<?php $this->section('custom_js') ?>
<script>
    $('.btn-export').on('click', function(e) {
        e.preventDefault();
        $('#form').attr('action', $(this).data('url'));
        $('#form').submit();
    });

    $("#select2-lowongan").select2({
        placeholder: 'Lowongan',
        width: "element",
    });

    $('#range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        },
        autoApply: true,
        startDate: moment().format('DD/MM/YYYY'),
        endDate: moment().format('DD/MM/YYYY')
    });

    $('#range').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('#range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    const educations = {
        '1' : 'SD',
        '2' : 'SMP / MTs',
        '3' : 'SMA / SMK',
        '4' : 'D3',
        '5' : 'S1',
        '6' : 'S2',
        '7' : 'S3',
    };
    $('.show').on('click', function(){
        let loader = `<div class="loader-wrapper">
                        <div class="loader-container">
                            <div class="ball-beat loader-purple">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    </div>`;
        let emptyState = `<div class="col-12 text-center mb-3">
                            <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
                        </div>`
        $('#result-section').html(loader)
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: siteUrl +  '<?= route_to('get_report_data') ?>',
            data: $('#form-report').serializeArray(),
            success: function(res){
                if(res.data.length){
                    const data = res.data;
                    let rows = '';
                    data.forEach((d)=> {
                        let respond = JSON.parse(d.respond_input);
                        let $image = "";
                        if (d.pas_photo) {
                            $image = $.cloudinary.url(d.pas_photo, {
                                        height: 50,
                                        width: 50,
                                        crop: "fill",
                                        cloud_name: "anas27",
                                        secure: true
                                    });
                        }
                        let $cv = "";
                        if (d.cv) {
                            const url = $.cloudinary.url(d.cv + '.png', {
                                        cloud_name: "anas27",
                                        secure: true
                                    });
                            $cv = '<a class="btn btn-primary btn-sm" href="' + url.replace('v1', 'fl_attachment') + '" download><i class="ft-download"></i> Download </a>';
                        }
                        rows += `<tr>
                                    <td class="pr-0 align-middle">
                                        <p class="mb-0 font-weight-bold">${ d.posisi }</p>
                                        <small>Di Apply pada : ${moment(d.created_at).format('DD MMMM, YYYY')}</small>
                                    </td>
                                    <td class="pr-0 pl-1">
                                        <div class="d-flex align-items-center">
                                            <img class="center-cropped mr-1" src="${ $image }" alt="avtar img holder" height="32" width="32">
                                            <div>
                                                <p class="mb-0 font-weight-bold">${ d.nama_lengkap }</p>
                                                <small>NIK: ${ d.nik ?? '-' }</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle pr-0 pl-1">
                                        <p class="mb-0 font-weight-bold">${educations[respond.last_education]} - ${respond.jurusan }</p>
                                        <small>${ respond.last_education < 4 ? 'Nilai' : 'IPK'} : ${respond.nilai_terakhir}</small>
                                    </td>
                                    <td class="align-middle pr-0 pl-1">
                                        ${ $cv }
                                    </td>
                                    <td class="align-middle pr-0 pl-1">
                                        <h5 class="font-weight-bold">${d.nilai_psikotest + ' / ' + d.nilai_minimum_psikotest}</h5>
                                    </td>
                                    <td class="align-middle pr-0 pl-1">
                                        <h5 class="font-weight-bold">${ d.nilai_interview }/100</h5>
                                    </td>
                                </tr>`
                        })
                    let content = `<table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="pr-0">Posisi</th>
                                            <th class="pr-0 pl-1">Pelamar</th>
                                            <th class="pr-0 pl-1">Pendidikan Terakhir</th>
                                            <th class="pr-0 pl-1">CV</th>
                                            <th class="pr-0 pl-1">Nilai Psikotest</th>
                                            <th class="pr-0 pl-1">Nilai Interview</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${rows}
                                    </tbody>
                                </table>`
                    $('#result-section').html(content)
                } else {
                    $('#result-section').html(emptyState)
                }
            }
        })
    })
</script>
<?php $this->endSection() ?>