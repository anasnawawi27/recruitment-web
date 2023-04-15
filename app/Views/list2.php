<?php $this->extend('layout/default') ?>
<?php $this->section('content') ?>
<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
    <h2 class="content-header-title mb-0 d-inline-block"><?php echo $heading ?></h2>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?>
        </div>
    </div>
</div>
<div class="d-flex h-100 card pb-5 mb-5 px-2">
    <div class="card-body px-0">
        <ul class="nav nav-tabs nav-underline">
            <li class="nav-item">
                <a href="#tab-draft" class="nav-link <?php echo !session()->getFlashdata('message') ? 'show active' : '' ?>" id="link-draft" data-toggle="tab"> <?php echo lang('PayrollData.draft'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#tab-generated" class="nav-link <?php echo session()->getFlashdata('message') ? 'show active' : '' ?>" id="link-generated" data-toggle="tab"> <?php echo lang('PayrollData.generated'); ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade p-2 <?php echo !session()->getFlashdata('message') ? 'show active' : '' ?>" id="tab-draft">
                <?php if ($hasProceed) : ?>
                    <div class="text-center py-4" id="has-proceed">
                        <h2 class="text-muted">Payroll for this month already proceed ...</h2>
                        <i class="la la-money text-muted" style="font-size:100px"></i>
                    </div>
                <?php else : ?>
                    <div class="row">
                        <div class="col-12 px-0 mb-1">
                            <button class="btn btn-secondary process mr-1"> Process </button>
                            <button class="btn btn-info generate d-none"> Generate </button>
                        </div>
                        <div class="col-12 px-0 table-responsive">
                            <table class="table" id="table-draft-payroll" data-toolbar="#toolbar-draft-payroll" data-buttons="buttonsDraft" data-show-button-text="true" data-pagination="true" data-search="false">
                                <thead>
                                    <tr>
                                        <th data-field="employee" data-width="900" data-sortable="true"><?php echo lang('PayrollData.employee'); ?></th>
                                        <th data-field="work_days" data-width="900"><?php echo lang('PayrollData.work_days'); ?></th>
                                        <th data-field="total_salary" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.salary'); ?></th>
                                        <th data-field="total_overtime" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.overtime'); ?></th>
                                        <th data-field="meal_allowance" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.meal'); ?></th>
                                        <th data-field="transport_allowance" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.transport'); ?></th>
                                        <th data-field="job_title_allowance" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.job_allowance'); ?></th>
                                        <th data-field="credit_allowance" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.credit_allowance'); ?></th>
                                        <th data-field="jht_num" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.jht'); ?></th>
                                        <th data-field="bpjs_ks_num" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.bpjs_ks'); ?></th>
                                        <th data-field="retire_insurance_num" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.retire'); ?></th>
                                        <th data-field="other_deduction" data-formatter="currencyFormatterDefault"><?php echo lang('PayrollData.other_deduction'); ?></th>
                                        <th data-formatter="actionFormatterDraft" data-width="20"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade <?php echo session()->getFlashdata('message') ? 'show active' : '' ?>" id="tab-generated">
                <?php if (session()->getFlashdata('message') !== NULL) : ?>
                    <div class="alert alert-success alert-dismissible fade show font-weight-bold text-white mt-2" role="alert">
                        <?php echo session()->getFlashdata('message'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php echo $this->include('table') ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.css">
<link href="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/sticky-header/bootstrap-table-sticky-header.css" rel="stylesheet">

<?php if (isset($pluginCSS)) {
    foreach ($pluginCSS as $file) {
        echo '<link href="' . $file . '" rel="stylesheet" type="text/css">';
    }
} ?>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/cookie/bootstrap-table-cookie.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>

<?php if (isset($pluginJS)) {
    foreach ($pluginJS as $file) {
        echo '<script src="' . $file . '"></script>';
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

<?php $this->section('custom_js'); ?>
<script src="<?php echo base_url('js/table.js?v=' . $_ENV['ASSETV']); ?>"></script>
<?php if (isset($customJS)) {
    foreach ($customJS as $file) {
        echo '<script src="' . $file . '?v=' . $_ENV['ASSETV'] . '"></script>';
    }
} ?>
<?php $this->endSection(); ?>