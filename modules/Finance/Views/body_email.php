<?php

use CodeIgniter\I18n\Time;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h2>Selamat Siang, <?php echo $name ?></h2>
    <p>Berikut kami kirimkan Slip Gaji bulan <?php echo Time::parse($date)->toLocalizedString('MMMM yyyy') ?>. Semoga Bermanfaat</p>
</body>

</html>