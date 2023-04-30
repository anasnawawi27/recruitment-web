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

<div style="margin: 0px; max-width: 100%; height: 100%;">
  <table style="width: 100%; max-width: 100%; height: 100%;" height="100%" bgcolor="#f2f2f2" border="0" cellspacing="0" cellpadding="0" align="center">
    <tbody>
      <tr>
        <td bgcolor="#f2f2f2">
          <div style="background-color: #f2f2f2;">
            <div style="margin: 0px auto; max-width: 600px;">
              <table style="width: 100%; max-width: 600px;" border="0" cellspacing="0" cellpadding="0" align="center">
                <tbody>
                  <tr>
                    <td style="direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;">
                      <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;">
                        <table style="vertical-align: top;" border="0" width="100%" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr style="background: #transparent; background-color: transparent; vertical-align: top;">
                              <td style="font-size: 0px; padding: 10px 25px; word-break: break-word;" align="left" width="50%">
                                <div style="font-family: Arial, sans-serif; font-size: 20px; line-height: 28px; text-align: left; color: #ffffff;">
                                  <img height="70" src="https://tekpakgroup.com/wp-content/uploads/2021/12/Tekpak.png">
                                </div>
                              </td>
                            </tr>
                              <?php 
                                $hour = date('H');
                                $dayTerm = ($hour > 17) ? "Sore" : (($hour > 12) ? "Siang" : "Pagi");   
                              ?>
                            <tr style="background: #ffffff; background-color: #ffffff; vertical-align: top;">
                              <td style="font-size: 0px; padding: 10px 25px; word-break: break-word;" align="left" colspan="2">
                                <div style="font-family: Arial, sans-serif; font-size: 13px; line-height: 28px; text-align: left; color: #000000;">
                                  Selamat <?= $dayTerm ?>, <?= $applicant->nama_lengkap ?>
                                </div>
                                <div style="font-family: Arial, sans-serif; font-size: 13px; line-height: 28px; text-align: left; color: #000000; margin-top: 20px">
                                  Sehubungan dengan lamaran anda untuk posisi <b><?= $applicant->posisi ?></b>, kami mengundang anda untuk mengikuti proses <b>Interview</b> yang akan dilaksanakan sebagai berikut :
                                </div>
                              </td>
                            </tr>
                            <tr style="background: #ffffff; background-color: #ffffff; vertical-align: top;">
                              <td style="font-size: 0px; padding: 10px 25px; word-break: break-word;" align="left" colspan="2">
                                <div style="font-family: Arial, sans-serif; font-size: 13px; line-height: 28px; text-align: left; color: #000000;">
                                  <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#000000;font-family:Arial, sans-serif;font-size:14px;line-height:22px;table-layout:auto;width:100%;border:none;">
                                    <tr style="text-align:left;padding:15px 0; font-size: 13px;">
                                      <td width="40%">Agenda</td>
                                      <td  width="60%">: <b><?= $interview->agenda ?></b></td>
                                    </tr>
                                    <tr style="text-align:left;padding:15px 0; font-size: 13px;">
                                      <td>Hari/Tanggal</td>
                                      <td>: <?=  Time::createFromFormat('Y-m-d', $interview->tanggal, 'Asia/Jakarta')->format('l, d M Y'); ?></td>
                                    </tr>
                                    <tr style="text-align:left;padding:15px 0; font-size: 13px;">
                                      <td>Waktu</td>
                                      <td>: Pukul <?= $interview->waktu ?> s/d selesai</td>
                                    </tr>
                                    <tr style="text-align:left;padding:15px 0; font-size: 13px;">
                                      <td>Pewawancara</td>
                                      <td>: <?= $interview->pewawancara ?></td>
                                    </tr>
                                    <?php if($interview->via == 'online') : ?>
                                    <tr style="text-align:left;padding:15px 0; font-size: 13px;">
                                      <td>Via</td>
                                      <td>: Online (Link : <?= $interview->link ?>)</td>
                                    </tr>
                                    <?php else : ?>
                                      <tr style="text-align:left;padding:15px 0; font-size: 13px;">
                                        <td style="vertical-align: top">Lokasi</td>
                                        <td>: <?= $interview->tempat ?></td>
                                      </tr>
                                    <?php endif ?>
                                  </table>
                                </div>
                              </td>
                            </tr>

                            <tr style="background-color: transparent; background: transparent; vertical-align: top;">
                              <td style="font-size: 0px; padding: 10px 25px; word-break: break-word;" colspan="2" align="left"> </td>
                            </tr>
                            <tr style="background: #ffffff; background-color: #ffffff; vertical-align: top;">
                              <td style="font-size: 0px; padding: 10px 25px; word-break: break-word;" colspan="2" align="left">
                                <div style="font-family: Arial, sans-serif; font-size: 13px; line-height: 28px; text-align: left; color: #000000;">
                                  <p>Demikian mengenai undangan <b>Proses Interview</b> ini, peserta interview diharapkan datang tepat waktu, minimum 10 menit sebelum interview dimulai.</p>
                                  <p> Atas perhatiannya, kami ucapkan terima kasih.</p>
                                </div>
                              </td>
                            </tr>

                            <tr style="background-color: transparent; background: transparent; vertical-align: top;">
                              <td style="font-size: 0px; padding: 10px 25px; word-break: break-word;" colspan="2" align="left"> </td>
                            </tr>
                           
                            <tr style="background: #999999; background-color: #999999; vertical-align: top;">
                              <td style="font-size: 0px; padding: 10px 25px; word-break: break-word;" colspan="2" align="left">
                              <div class="mj-column-per-50 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                  <tr>
                                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                      <div style="font-family:Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#ffffff;">
                                        <b>PT Tekpak Indonesia</b>
                                        <br/>
                                        JL Jababeka 4, SFB Blok T-1A, Cikarang Industrial Estate, Jawa Barat 17530<br/>
                                        Telepon: (021) 8935090
                                    </td>
                                  </tr>
                                </table>
                              </div>
                          </tbody>
                        </table>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
</body>

</html>