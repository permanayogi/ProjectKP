<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\SuratMasukModel;

class Notifikasi extends BaseController
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
    }

    public function countNotification()
    {
        $suratMasukModel = $this->suratMasukModel;
        if ($this->request->isAJAX()) {
            $result = $suratMasukModel->countNotification();
            if ($result) {
                $this->output['success'] = true;
                $this->output['count']   = $result;
            } else {
                $this->output['success'] = true;
                $this->output['count']   = $result;
            }
            echo json_encode($this->output);
        }
    }

    public function notifikasi()
    {
        $data = $this->suratMasukModel->notifikasi();
        foreach ($data as $result) {
            $originalDate =  $result['tanggal_surat'];
            $newFormatDate = date("d-m-Y", strtotime($originalDate));
            $nomor_surat = $result['no_surat'];
            $nomor_agenda = $result['no_agenda'];
            $perihal = $result['perihal'];
            $status = $result['status'];
            if ($status == 'Belum di proses') {
                echo "
                <a href='" . base_url('guru/suratmasuk') . "'  class='dropdown-item dropdown-item-unread'>
                    <div class='dropdown-item-icon bg-primary text-white'>
                    <i class='fas fa-envelope'></i>
                    </div>
                    <div class='dropdown-item-desc'>
                        <b>$newFormatDate;</b><br>
                        <b>No Surat/Agenda : $nomor_surat/$nomor_agenda </b><br>
                        <p>$perihal</p>
                    </div>
                </a>";
            } else if ($status == 'Sudah di proses') {
                echo "
                <a href = '" . base_url('guru/suratmasuk') . "'class='dropdown-item dropdown-item'>
                    <div class='dropdown-item-icon bg-primary text-white'>
                        <i class='fas fa-envelope'></i>
                    </div>
                    <div class='dropdown-item-desc'>
                        <b>$newFormatDate;</b><br>
                        <b>No Surat/Agenda : $nomor_surat/$nomor_agenda  </b><br>
                        <p>$perihal</p>
                    </div>
                </a>";
            }
        }
    }
}
