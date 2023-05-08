<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Profile</title>
</head>
<body>
    <?php
    use CodeIgniter\I18n\Time; 
    $educations = [
        '1' => 'SD',
        '2' => 'SMP / MTs',
        '3' => 'SMA / SMK',
        '4' => 'D3',
        '5' => 'S1',
        '6' => 'S2',
        '7' => 'S3',
    ];
    ?>
    <?php foreach($rows as $index => $data) : ?>
        <table width="100%">
            <tr>
                <td width="25%" style="padding: 15px; vertical-align:top">
                    <?php
                        $cld = new \Cloudinary\Cloudinary(CLD_CONFIG);
                        $base64 = base64_encode(file_get_contents($cld->image($data->pas_photo)));
                        $image = 'data:image/jpg;base64,' . $base64;
                    ?>
                    <img width="100%" src="<?= $image ?>" alt="profile-image">
                </td>
                <td width="75%" style="padding: 15px">
                    <h6 style="margin-bottom: 10px; margin-top: 0; font-size: 15px">Summary</h6>
                    <table width="100%" style="border: 1px solid #D1D1D1; border-collapse: collapse;">
                        <tr>
                            <td width="50%" style="padding: 10px">
                                <small style="font-size: 13px">Nama Lengkap</small>
                                <p style="font-weight: bold; margin-top: 5px"><?= $data->nama_lengkap ?></p>
                            </td>
                            <td width="50%" style="padding: 10px">
                                <small style="font-size: 13px">Posisi</small>
                                <p style="font-weight: bold; margin-top: 5px"><?= $data->posisi ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="border-top: 1px solid #D1D1D1; border-right: 1px solid #D1D1D1; padding: 10px">
                                <small style="font-size: 13px">Nilai Psikotest</small>
                                <p style="font-weight: bold; margin-top: 5px; font-size: 25px; margin-bottom:10px"><?= $data->nilai_psikotest ?>/<?= $data->nilai_minimum_psikotest ?></p>
                            </td>
                            <td width="50%" style="border-top: 1px solid #D1D1D1; padding: 10px">
                                <small style="font-size: 13px">Nilai Interview</small>
                                <p style="font-weight: bold; margin-top: 5px; font-size: 25px; margin-bottom:10px"><?= $data->nilai_interview ?>/100</p>
                            </td>
                        </tr>
                    </table>
                    <?php $respond = json_decode($data->respond_input) ?>
                    <h6 style="margin-bottom: 10px; margin-top: 20px; font-size: 15px">Detail</h6>
                    <table width="100%" style="border: 1px solid #D1D1D1; border-collapse: collapse;">
                        <tr style="background-color:#ECECEC">
                            <td width="35%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">Jenis Kelamin</p>
                            </td>
                            <td width="65%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">: <?= ucwords($data->jenis_kelamin) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">Pendidikan Terakhir</p>
                            </td>
                            <td width="65%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">: <?= $educations[$respond->last_education] ?> (<?= $respond->jurusan ?>). <strong><?= $respond->last_education < 4 ? 'Nilai ' : 'IPK ' ?> : <?= $respond->nilai_terakhir ?></strong></p>
                            </td>
                        </tr>
                        <tr style="background-color:#ECECEC">
                            <td width="35%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">Pengalaman Kerja</p>
                            </td>
                            <td width="65%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">: 
                                <?php if(!$respond->berpengalaman){
                                    echo 'Fresh Graduate';
                                } else {
                                    echo 'Berpengalaman ' . $respond->lama_pengalaman . ' Tahun';
                                } ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">Tempat Tanggal Lahir</p>
                            </td>
                            <td width="65%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">: <?= $data->tempat_lahir ?>, <?= Time::parse($data->tanggal_lahir)->toLocalizedString('d MMMM yyyy'); ?></p>
                            </td>
                        </tr>
                        <tr style="background-color:#ECECEC">
                            <td width="35%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">Email</p>
                            </td>
                            <td width="65%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">: <?= $data->email ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">NIK</p>
                            </td>
                            <td width="65%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">: <?= $data->nik ?></p>
                            </td>
                        </tr>
                        <tr style="background-color:#ECECEC">
                            <td width="35%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">No Handphone 1</p>
                            </td>
                            <td width="65%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">: <?= $data->no_handphone_1 ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">No Handphone 2</p>
                            </td>
                            <td width="65%" style="padding: 10px">
                                <p style="margin: 0px; font-size: 14px">: <?= $data->no_handphone_2 ?></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <?php endforeach ?>
</body>
</html>