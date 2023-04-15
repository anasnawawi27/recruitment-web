<?php

use CodeIgniter\I18n\Time; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Slip</title>
</head>
<style>
    table {
        border-collapse: collapse;
    }

    th,
    td {
        padding: 5px;
    }

    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }

    .body-payroll tr td {
        padding: 10px 20px 0px 20px;
    }
</style>

<body>
    <table>
        <tbody>
            <tr>
                <td>
                    <img src="<?php echo $logo ?>" style="width:50px">
                </td>
                <td style="padding-left:10px; padding-bottom:10px">
                    <div>
                        <p style="margin-bottom:5px; font-weight:bold">PT Mulia Bintang Kejora</p>
                        <small style="color:#00A0E8">Melayani, Berkarya, Komitmen</small>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="text-align:center">
        <h2 style="margin-bottom:0px; margin-top:0">PAYSLIP</h2>
        <p style="margin-bottom:0px; margin-top:5px"><?php echo Time::parse($data->date)->toLocalizedString('MMMM, yyyy'); ?></p>
        <hr>
    </div>
    <div>
        <table style="font-size:12px; width:100%">
            <tbody>
                <tr>
                    <td style="width:15%">Employee ID</td>
                    <td style="width:80%">: <?php echo $data->employee_id ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>: <?php echo $data->fullname ?></td>
                </tr>
                <tr>
                    <td>Job Title</td>
                    <td>: <?php echo $data->job_title ?></td>
                </tr>
                <tr>
                    <td>Workplace</td>
                    <td>: <?php echo $data->workplace ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">
        <table style="width:100%">
            <thead>
                <tr>
                    <td style="width:50%" colspan="2" style="font-weight:bold; background-color:#EBEBEB; text-align:center">INCOME (+)</td>
                    <td style="width:50%" colspan="2" style="font-weight:bold; background-color:#EBEBEB; text-align:center">DEDUCTION (-)</td>
                </tr>
            </thead>
            <tbody class="body-payroll">
                <tr>
                    <td style="width:25%">Gaji Pokok</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($data->total_salary) ?></td>
                    <td style="width:25%">JHT</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($detail->jht) ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Tunjangan Konsumsi</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($detail->meal_allowance) ?></td>
                    <td style="width:25%">BPJS KS</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($detail->bpjs_ks) ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Tunjangan Transport</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($detail->transport_allowance) ?></td>
                    <td style="width:25%">Jaminan Pensiun</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($detail->retire_insurance) ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Tunjangan Jabatan</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($detail->job_title_allowance) ?></td>
                    <td style="width:25%">Potongan Lain-lain</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($detail->other_deduction) ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Tunjangan Pulsa</td>
                    <td style="width:25%; text-align:right">Rp. <?php echo number_format($detail->credit_allowance) ?></td>
                    <td style="width:25%"></td>
                    <td style="width:25%;"></td>
                </tr>
                <tr>
                    <td style="width:25%; padding-bottom:20px">Lembur</td>
                    <td style="width:25%; text-align:right; padding-bottom:20px">Rp. <?php echo number_format($data->total_overtime) ?></td>
                    <td style="width:25%"></td>
                    <td style="width:25%;"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td style="width:25%; padding:5px 20px; border-top:1px solid #D1D1D1; border-bottom:1px solid #D1D1D1">Total Income</td>
                    <td style="width:25%; text-align:right; padding:5px 20px; border-top:1px solid #D1D1D1; border-right:1px solid #D1D1D1; border-bottom:1px solid #D1D1D1">
                        <h3>Rp. <?php echo number_format(($data->total_salary + $data->total_overtime + $data->total_allowance)) ?></h3>
                    </td>
                    <td style="width:25%; padding:5px 20px; border-top:1px solid #D1D1D1; border-bottom:1px solid #D1D1D1">Total Deduction</td>
                    <td style="width:25%; text-align:right; padding:5px 20px; padding:10px 20px; border-top:1px solid #D1D1D1; border-bottom:1px solid #D1D1D1">
                        <h3>Rp. <?php echo number_format($data->total_deduction) ?></h3>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="text-align:right; margin-top:20px">
        <p style="color:#7D7F90; margin-bottom:0">Take Home Pay</p>
        <h2 style="margin-top:15px">Rp. <?php echo number_format($data->take_home_pay) ?></h2>
    </div>
    <div style="font-size:13px">
        <small>Generated By Finance Dept</small><br>
        <small><?php echo Time::parse($data->date)->toLocalizedString('dd, MMMM yyyy'); ?></small>
    </div>
</body>

</html>