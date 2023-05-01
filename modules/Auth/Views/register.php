<?php $this->extend('layout/auth') ?>
<?php $this->section('content') ?>
<div class="col-md-7 col-10 box-shadow-2 p-0 mt-3">
    <div class="card border-grey border-lighten-3 px-1 py-1 m-0 col-12">
        <div class="card-header border-0 pb-0">
            <div class="card-title text-center">
                <h4 class="font-weight-bold">Registrasi</h4>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="mt-2" id="message"></div>
                <form class="form-horizontal" id="register" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fullname"><?= lang('Auth.fullname') ?></label>
                                <input type="text" id="fullname" class="form-control" name="nama_lengkap">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= lang('Auth.gender') ?></label>
                                <div class="input-group">
                                    <div class="d-inline-block custom-control custom-radio mr-1">
                                        <input type="radio" name="jenis_kelamin" checked class="custom-control-input" id="gender-1" value="laki-laki">
                                        <label class="custom-control-label" for="gender-1">Laki-Laki</label>
                                    </div>
                                    <div class="d-inline-block custom-control custom-radio">
                                        <input type="radio" name="jenis_kelamin" class="custom-control-input" id="gender-2" value="perempuan">
                                        <label class="custom-control-label" for="gender-2">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birthplace"><?= lang('Auth.birthplace') ?></label>
                                <input type="text" id="birthplace" class="form-control" name="tempat_lahir">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birthdate"><?= lang('Auth.birthdate') ?></label>
                                <input type="date" id="birthdate" class="form-control" name="tanggal_lahir">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no-handphone-1"><?= lang('Auth.no_handphone_1') ?></label>
                                <input type="number" id="no-handphone-1" class="form-control" name="no_handphone_1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no-handphone-2"><?= lang('Auth.no_handphone_2') ?></label>
                                <input type="number" id="no-handphone-2" class="form-control" name="no_handphone_2">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email"><?= lang('Auth.email') ?></label>
                                <input type="text" id="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username"><?= lang('Auth.username') ?></label>
                                <input type="text" id="username" class="form-control" name="username" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password"><?= lang('Auth.password') ?></label>
                                <input type="password" id="password" class="form-control" name="password" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm-password"><?= lang('Auth.confirm_password') ?></label>
                                <input type="password" id="confirm-password" class="form-control" name="konfirmasi_password">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success btn-lighten-5 text-white btn-block btn-glow btn-shadow submit">
                        <i class="ft-user"></i> <?php echo lang('Auth.btn_register') ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-footer">
          <div class="">
            <p class="float-xl-right text-center m-0">Sudah punya akun ? <a href="<?= base_url('login') ?>" class="card-link">Login</a></p>
          </div>
        </div>
    </div>
</div>
<?php $this->endSection() ?>

<?php $this->section('custom_js'); ?>
<script src="<?php echo base_url('js/modules/auth.js?v=' . $_ENV['ASSETV']); ?>"></script>
<?php $this->endSection(); ?>