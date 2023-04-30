<?php $this->extend('layout/default2') ?>
<?php $this->section('content')?>
<?php use CodeIgniter\I18n\Time; ?>
<div class="row">
    <?php if($data) : ?>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <a href="<?= route_to('job_applications') ?>" class="mr-1 mb-1 btn btn-outline-secondary text-left border-0 btn-min-width">
                    <i class="la la-arrow-left"></i> Kembali
                </a>
                <div class="row">
                    <div class="col-12">
                        <?php $cld = new \Cloudinary\Cloudinary(CLD_CONFIG); ?>
                        <div class="bg" style="background-image: url(<?= !$data->gambar ? base_url('images/illustrations/lowongan-default.png') : $cld->image($data->gambar) ?>)"></div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="d-flex mb-2 align-items-center">
                            <i class="icon-briefcase secondary font-large-1"></i>
                            <h3 class="font-weight-bolder mb-0 ml-1"><?= $data->posisi ?></h3>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <span class="badge-status mb-0 status-<?= $data->status ?> text-capitalize">
                                <?= ucwords(str_replace('_', ' ' , $data->status)) ?>
                            </span>
                            <span class="font-weight-bolder d-inline-block mx-1"> | </span>
                            <span> Di-Apply pada :
                            <?php
                                $time = Time::parse($data->created_at, 'Asia/Jakarta');
                                echo $time->toLocalizedString('d MMMM, yyyy');
                            ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
            <?php if($data->status == 'passed') : ?>
                <div class="text-center">
                    <img class="w-50 mb-2" src="<?= base_url('images/illustrations/worker.png') ?>" alt="worker">
                    <h4 class="font-weight-bolder mb-1">Lamaranmu sudah berhasil di apply !</h4>
                    <p>Datamu belum lengkap. Silahkan Lengkapi data!</p>
                    <a href="<?= route_to('complete_data', $data->id_lowongan) ?>" class="btn btn-glow btn-primary">Lengkapi Data</a>
                </div>
                <?php endif ?>
                <?php if($data->status == 'applied' || $data->status == 'failed_psikotest') : ?>
                    <h5 class="font-weight-bolder mb-1">Tahap Psikotest</h5>
                    <div class="card shadow-none border-secondary border-lighten-5">
                        <div class="card-body">
                            <div class="mb-2 border-bottom">
                                <div class="form-group">
                                    <label>Kategori Soal</label>
                                    <h6 class="font-weight-bolder"><?= $kategori_soal ?></h6>
                                </div>
                            </div>
                            <div class="d-md-flex justify-content-between">
                                <div class="form-group">
                                    <label>Waktu Pengerjaan</label>
                                    <h6 class="font-weight-bolder"><?= $data->waktu_pengerjaan ?> Menit</h6>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Soal</label>
                                    <h6 class="font-weight-bolder"><?= $jumlah_soal ?></h6>
                                </div>
                                <div class="form-group">
                                    <label>Point Persoal</label>
                                    <h6 class="font-weight-bolder"><?= $data->point_persoal ?></h6>
                                </div>
                            </div>
                            <?php if($data->status == 'failed_psikotest') : ?>
                                <div class="alert alert-danger mb-2" role="alert">
                                    <strong>Mohon maaf anda gagal di tahap Psikotest !</strong>
                                </div>
                            <?php else : ?>
                                <div class="button-working-it">
                                    <button type="button" class="btn btn-primary btn-lg btn-block working-it">
                                        Kerjakan Sekarang
                                    </button>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
                <?php if($data->status == 'interview') : ?>
                    <h5 class="font-weight-bolder mb-1">Tahap Interview</h5>
                    <div class="card shadow-none border-secondary border-lighten-5">
                        <div class="card-body">
                            <h3 class="font-weight-bold">Anda lanjut ke tahap Interview.</h3>
                            <?php if($interview) : ?>
                                <div class="w-100 text-center">
                                    <img class="img-fluid w-50 my-3" alt="image-psikotest" src="<?= base_url('images/illustrations/interview.jpg') ?>">
                                </div>
                                <p>Mengenai detail proses Interview dapat anda simak dibawah ini :</p>
                                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                                    <tr style="text-align:left;padding:15px 0">
                                      <td width="25%">Agenda</td>
                                      <td  width="65%">: <b><?= $interview->agenda ?></b></td>
                                    </tr>
                                    <tr style="text-align:left;padding:15px 0">
                                      <td>Hari/Tanggal</td>
                                      <td>: <?=  Time::createFromFormat('Y-m-d', $interview->tanggal, 'Asia/Jakarta')->format('l, d M Y'); ?></td>
                                    </tr>
                                    <tr style="text-align:left;padding:15px 0">
                                      <td>Waktu</td>
                                      <td>: Pukul <?= $interview->waktu ?> s/d selesai</td>
                                    </tr>
                                    <tr style="text-align:left;padding:15px 0">
                                      <td>Pewawancara</td>
                                      <td>: <?= $interview->pewawancara ?></td>
                                    </tr>
                                    <?php if($interview->via == 'online') : ?>
                                    <tr style="text-align:left;padding:15px 0">
                                      <td>Via</td>
                                      <td>: Online (Link : <?= $interview->link ?>)</td>
                                    </tr>
                                    <?php else : ?>
                                      <tr style="text-align:left;padding:15px 0">
                                        <td style="vertical-align: top">Lokasi</td>
                                        <td>: <?= $interview->tempat ?></td>
                                      </tr>
                                    <?php endif ?>
                                </table>
                                <p class="mt-1">Peserta interview diharapkan datang tepat waktu, minimum 10 menit sebelum interview dimulai.</p>
                            <?php else : ?>
                                <div class="alert alert-warning mb-2" role="alert">
                                    Jadwal Interview belum di-atur oleh admin. Silahkan menunggu dan periksa email anda secara berkala terkait informasi jadwal interview.
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
                <?php if($data->status == 'accepted') : ?>
                    <h3 class="font-weight-bold text-center">Anda lolos proses Recruitment!</h3>
                    <div class="w-100 text-center">
                        <img class="img-fluid w-25 my-3" alt="image-accepted" src="<?= base_url('images/illustrations/accepted.svg') ?>">
                    </div>
                    <p class="text-center mx-3">Selamat ! Anda lolos proses recruitment untuk posisi <?= $data->posisi ?>. Tahap selanjutnya akan diinfokan oleh PT Tekpak Indonesia</p>
                    <div class="row border-top px-2">
                        <div class="col-6 text-center border-right pt-1">
                            <p class="mb-0">Nilai Psikotest</p>
                            <h3 class="font-weight-bold"><?= $data->nilai_psikotest ?></h3>
                        </div>
                        <div class="col-6 text-center pt-1">
                            <p class="mb-0">Nilai Interview</p>
                            <h3 class="font-weight-bold"><?= $data->nilai_interview ?></h3>
                        </div>
                    </div>
                <?php endif ?>
                <?php if($data->status == 'failed') : ?>
                    <h3 class="font-weight-bold text-center">Anda Tidak Lolos Proses Recruitment !</h3>
                    <div class="w-100 text-center">
                        <img class="img-fluid w-25 my-3" alt="image-failed" src="<?= base_url('images/illustrations/failed.svg') ?>">
                    </div>
                    <p class="text-center mx-3">Mohon Maaf, Anda belum memenuhi kualifikasi untuk posisi <?= $data->posisi ?> sesuai yang kami cari.</p>
                    <div class="row border-top px-2">
                        <div class="col-6 text-center border-right pt-1">
                            <p class="mb-0">Nilai Psikotest</p>
                            <h3 class="font-weight-bold"><?= $data->nilai_psikotest ?></h3>
                        </div>
                        <div class="col-6 text-center pt-1">
                            <p class="mb-0">Nilai Interview</p>
                            <h3 class="font-weight-bold"><?= $data->nilai_interview ?></h3>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php else : ?>
    <div class="col-12 text-center">
        <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
    </div>
    <?php endif ?>
</div>
<?php $this->endSection(); ?>

<?php $this->section('custom_css'); ?>
<style>
    .bg{
        width: 100%;
        height: 150px;
        border-radius: 10px;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<script>
    $(document).ready(function(){
        if(localStorage.getItem('psikotest-start-<?= $data->posisi ?>')){
            let countDownDate = Number(localStorage.getItem('countdown-date'));

            let myfunc = setInterval(function() {
                let now = new Date().getTime();
                let timeleft = countDownDate - now;

                let days = Math.floor(timeleft / (1000 * 60 * 60 * 24)).toString();
                let hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString();
                let minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60)).toString();
                let seconds = Math.floor((timeleft % (1000 * 60)) / 1000).toString();

                let counterLabel = `${days.length > 1 ? days : '0' + days }:${hours.length > 1 ? hours : '0' + hours}:${minutes.length > 1 ? minutes : '0' + minutes}:${seconds.length > 1 ? seconds : '0' + seconds}`;
                $('.button-working-it').html(`<a href="${siteUrl + '<?= route_to('psikotest_session', $data->id) ?>'}" class="btn btn-primary btn-lg btn-block">
                                    Lanjutkan Mengerjakan : ${counterLabel}
                                </a>`);

                if (timeleft < 0) {
                    clearInterval(myfunc);
                    $('.button-working-it').html(`<div class="alert alert-danger mb-2" role="alert"><strong>Waktu Pengerjaan Habis !</strong></div>`);
                }
                    
            }, 1000);
        }
    })

    $('.working-it').on('click', function(){
        Swal.fire({
            title: 'Sudah siap ?',
            text: 'Sesi Psikotest akan dimulai. Waktu pengerjaan <?= $data->waktu_pengerjaan . ' Menit' ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                let start = new Date();
                let countDownDate = new Date(start.setMinutes(start.getMinutes() + parseInt('<?= $data->waktu_pengerjaan?>')))

                localStorage.setItem('psikotest-start-<?= $data->posisi ?>', 'TRUE');
                localStorage.setItem('countdown-date', countDownDate.getTime().toString());

                window.location.href = siteUrl +  '<?= route_to('psikotest_session', $data->id) ?>';
            }
        })
    })
</script>
<?php $this->endSection(); ?>
