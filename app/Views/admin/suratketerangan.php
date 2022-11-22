<html>

<head>
    <style>
        .halaman {
            text-align: justify;
            line-height: 1.6;
            padding-left: 30px;
            padding-right: 30px;
        }
    </style>

</head>

<body>
    <div class="halaman">
        <p style="margin-left: 25px;">Yang bertanda tangan di bawah ini Kepala SMA N 1 Tanjung Jabung Barat, dengan ini menerangkan bahwa :</p>

        <table style="padding-left: 20p;">
            <tr>
                <td style="width: 30%;">Nama</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;"><?= $dataSuratKeterangan['nama_siswa'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">NIS/NISN</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;"><?= $dataSuratKeterangan['nisn'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%; vertical-align: top;">Tempat Tgl Lahir</td>
                <td style="width: 5%; vertical-align: top;">:</td>
                <td style="width: 65%;"><?= $dataSuratKeterangan['ttl'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">Kelas</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;"><?= $dataSuratKeterangan['kelas'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">Nama Orang Tua</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;"><?= $dataSuratKeterangan['orang_tua'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;">Alamat</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;"><?= $dataSuratKeterangan['alamat'] ?></td>
            </tr>
        </table>

        <p>Benar nama yang tersebut diatas terdaftar sebagai peserta didik kelas <?= $dataSuratKeterangan['kelas'] ?> di SMA N 1 Tanjung Jabung Barat pada tahun ajaran <?= $tahunAjar ?> .</p>
        <p>Demikian Surat Keterangan ini kami buat dengan sebenarnya untuk dapat diproses sebagaimana mestinya.</p><br><br>

        <p style="text-align: right;">Kuala Tungkal, <?= $tanggal ?><br />
            Kepala,</p><br><br />
        <p style="text-align: right;"><strong>KADIMAN, ST</strong><br />
            NIP. 19760609 200701 1 015</p>
    </div>

</body>

</html>