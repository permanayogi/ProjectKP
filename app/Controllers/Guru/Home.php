<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\SuratMasukModel;
use App\Models\DisposisiModel;

class Home extends BaseController
{
    protected $suratMasukModel;
    public $output = [
        'success' => false,
        'message' => '',
        'data' => []
    ];

    public function __construct()
    {
        $this->suratMasukModel = new SuratMasukModel();
        $this->disposisiModel = new DisposisiModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Home',
        ];
        return view('guru/home', $data);
    }


    // public function getDisposisi($id_surat)
    // {
    //     $data = $this->disposisiModel->getData($id_surat);
    //     $i = 1;
    //     foreach ($data as $row) {
    //         echo "<tr>";
    //         echo "<td>" . $i . "</td>";
    //         echo "<td>" . $row['tanggal_disposisi'] . "</td>";
    //         echo "<td>" . $row['Dari'] . "</td>";
    //         echo "<td>" . $row['isi'] . "</td>";
    //         echo "<td>" . $row['Kepada'] . "</td>";
    //         if (session()->get('id') == $row['id_dari']) {
    //             echo '<td><a href="#" onclick="hapusDisposisi(' . $row['id_disposisi'] . ', ' . $row['id_surat '] . ') "title="Delete"class="btn btn-icon btn-sm btn-danger"><i class="far fa-trash-alt"></i></a></td>';
    //         } else {
    //             echo '<td><a href="#"class="btn btn-sm btn-outline-danger"><i class="far fa-trash-alt"></i></a></td>';
    //         }
    //         $i++;
    //     }
    //     // echo json_encode($disposisi);
    // }
}
