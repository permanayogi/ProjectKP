<html>

<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        hr {
            border: 1px solid black;
        }

        td,
        th {
            border: 1px solid #000000;
            text-align: center;
            height: 20px;
            margin: 8px;
        }
    </style>
</head>

<body>
    <!-- <hr> -->
    <table>
        <thead>
            <tr>
                <th><strong>No</strong></th>
                <th><strong>Tanggal Surat</strong></th>
                <th><strong>Nomor Surat</strong></th>
                <th><strong>Nomor Agenda</strong></th>
                <th><strong>Sifat Surat</strong></th>
                <th><strong>Perihal</strong></th>
                <th><strong>Pengirim</strong></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($data as $row) {
            ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= date('d-F-Y', strtotime($row->tanggal_surat)) ?></td>
                    <td><?= $row->no_surat  ?></td>
                    <td><?= $row->no_agenda ?></td>
                    <td><?= $row->sifat_surat ?></td>
                    <td><?= $row->perihal ?></td>
                    <td><?= $row->pengirim ?></td>
                </tr>
            <?php $i++;
            } ?>
        </tbody>
    </table>
</body>

</html>