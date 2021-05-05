<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DisposisiModel;
use App\Models\SuratMasukModel;

class Disposisi extends BaseController
{
    public function __construct()
    {
        $this->disposisiModel = new DisposisiModel();
        $this->suratMasukModel = new SuratMasukModel();
    }

    public function getDisposisi($id_surat)
    {
        $data = $this->disposisiModel->getData($id_surat);
        $i = 1;
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>" . $i . "</td>";
            echo "<td>" . $row['tanggal_disposisi'] . "</td>";
            echo "<td>" . $row['Dari'] . "</td>";
            echo "<td>" . $row['isi'] . "</td>";
            echo "<td>" . $row['Kepada'] . "</td>";
            $i++;
        }
        // echo json_encode($disposisi);
    }



    public function store()
    {
        $disposisiModel = $this->disposisiModel;
        $suratMasukModel = $this->suratMasukModel;
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'isi'           => 'required',
            ])) {
                $this->output['errors'] = $validation->getErrors();
                echo json_encode($this->output);
            } else {
                $dari = session()->get('id');
                $id_surat = $this->request->getPost('id_surat');
                $kepada = $this->request->getPost('users');
                $isi = $this->request->getPost('isi');
                $data = [
                    'tanggal_disposisi'           => date('Y-m-d'),
                    'dari'  => $dari,
                    'kepada'        => $kepada,
                    'isi' => $isi,
                    'status' => 'Belum di proses',
                    'id_surat' => $id_surat
                ];
                if (session()->get('level') == 'admin' || session()->get('level') == 'kepsek') {
                    $disposisi = [
                        'id_surat' => $id_surat,
                        'disposisi' => 1
                    ];
                    $update = $suratMasukModel->save($disposisi);
                }
                $save = $disposisiModel->save($data);
                if ($save) {
                    $this->output['success'] = true;
                    $this->output['message'] = 'Disposisi surat success.';
                }
                echo json_encode($this->output);
            }
        }
    }
    public function getStatus($id_disposisi)
    {
        $disposisiModel = $this->disposisiModel;
        if ($this->request->isAJAX()) {
            $result = $disposisiModel->getStatus($id_disposisi);

            if ($result) {
                $this->output['success'] = true;
                $this->output['message']  = 'Data ditemukan';
                $this->output['data']   = $result;
            }
            echo json_encode($this->output);
        }
    }

    public function updateStatus($id_disposisi)
    {
        $disposisiModel = $this->disposisiModel;
        if ($this->request->isAJAX()) {
            $data = [
                'status' => 'Sudah di proses'
            ];
            $update = $disposisiModel->update($id_disposisi, $data);
            if ($update) {
                $this->output['success'] = true;
                $this->output['message'] = 'Record has been added successfully.';
            }
            echo json_encode($this->output);
        }
    }
}
