<?php

use CodeIgniter\I18n\Time; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Payroll</title>
</head>
<style>
    html {
        margin: 20px;
        padding: 0px;
    }

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

    #content tr td,
    #content tr th {
        padding: 10px;
        border: 1px solid grey;
    }

    #content tr th {
        background-color: #EBECF1;
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
        <h2 style="margin-bottom:0px; margin-top:0">Report Payroll</h2>
        <hr>
    </div>
    <?php if ($employee || $monthYear) : ?>
        <div>
            <table>
                <tbody>
                    <?php if ($employee) : ?>
                        <tr>
                            <td style="width:30%">Employee</td>
                            <td style="width:50%">: <?php echo $employee ?></td>
                        </tr>
                    <?php endif ?>
                    <?php if ($monthYear) : ?>
                        <tr>
                            <td style="width:30%">Month</td>
                            <td style="width:50%">: <?php echo Time::parse($monthYear)->toLocalizedString('MMMM') ?></td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <div style="margin-top:20px">
        <table id="content" style="width: 100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Salary</th>
                    <th>Overtime</th>
                    <th>Total Allowance</th>
                    <th>Total Deduction</th>
                    <th>Take Home pay</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data) : ?>
                    <?php foreach ($data as $row) : ?>
                        <tr>
                            <td><?php echo Time::parse($row->date)->toLocalizedString('dd/MM/yyyy') ?></td>
                            <td><?php echo $row->employee_name ?></td>
                            <td style="text-align:right">Rp. <?php echo number_format($row->total_salary) ?></td>
                            <td style="text-align:right">Rp. <?php echo number_format($row->total_overtime) ?></td>
                            <td style="text-align:right">Rp. <?php echo number_format($row->total_allowance) ?></td>
                            <td style="text-align:right">Rp. <?php echo number_format($row->total_deduction) ?></td>
                            <td style="text-align:right">Rp. <?php echo number_format($row->take_home_pay) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 5px">
                            <h4>No Data Found</h4>
                        </td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</body>

</html>