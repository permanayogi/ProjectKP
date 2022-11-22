<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\SuratMasukModel;
use App\Models\DisposisiModel;

class Suratmasuk extends BaseController
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
        return view('guru/suratmasuk', $data);
    }

    public function ajax_list()
    {
        $suratMasukModel = $this->suratMasukModel;
        $jabatan = session()->get('jabatan');
        $where = ['disposisi_per_user.kepada' => $jabatan];
        $column_order = array('', 'tanggal_surat', 'no_surat', 'no_agenda', 'sifat_surat', 'pengirim', 'status', '');
        $column_search = array('no_surat', 'no_agenda', 'pengirim');
        $order = array('tanggal_surat' => 'DESC');
        $list = $suratMasukModel->get_datatables('surat_masuk', $column_order, $column_search, $order, $where);
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $lists) {
            $no++;
            $row = array();
            $detail = '<a href="#" onclick="detail(' . $lists->id_surat . ', ' . $lists->id_disposisi .  ') "title="Detail" class="btn btn-icon btn-sm btn-info"><i class="fas fa-info-circle"></i></a>';
            $row[] = $no;
            $row[] = date("d-m-Y", strtotime($lists->tanggal_surat));
            $row[] = $lists->no_surat;
            $row[] = $lists->no_agenda;
            $row[] = $lists->sifat_surat;
            $row[] = $lists->pengirim;
            $row[] = $lists->status;
            $row[] = $detail;
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : null,
            "recordsTotal" => $suratMasukModel->count_all('surat_masuk', $where),
            "recordsFiltered" => $suratMasukModel->count_filtered('surat_masuk', $column_order, $column_search, $order, $where),
            "data" => $data,
        );
        echo json_encode($output);
    }
}
