<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DisposisiModel;
use App\Models\SuratMasukModel;
use App\Models\Disposisiperusermodel;

class Disposisi extends BaseController
{
    protected $disposisiPerUser;
    public function __construct()
    {
        $this->disposisiModel = new DisposisiModel();
        $this->suratMasukModel = new SuratMasukModel();
        $this->disposisiPerUser = new Disposisiperusermodel();
    }

    public function getDisposisi($id_surat)
    {
        $data = $this->disposisiModel->getData($id_surat);
        $i = 1;
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>" . $i . "</td>";
            echo "<td>" . $row['tanggal_disposisi'] . "</td>";
            echo "<td>" . $row['dari'] . "</td>";
            echo "<td>" . $row['isi'] . "</td>";
            echo "<td>" . $row['kepada'] . "</td>";
            if (session()->get('level') == 'guru') {
                if (session()->get('jabatan') == $row['dari']) {
                    echo '<td><a href="#" onclick="editDisposisi(' . $row['id_disposisi'] . ',' . "'" . $row['kepada'] . "',"  . $id_surat .  ') "title="Edit"class="btn btn-icon btn-sm btn-warning"><i class="far fa-edit"></i></a>&nbsp;&nbsp;';
                    echo '<a href="#" onclick="hapusDisposisi(' . $row['id_disposisi'] . ') "title="Delete"class="btn btn-icon btn-sm btn-danger"><i class="far fa-trash-alt"></i></a></td>';
                } else {
                    echo '<td><a href="#"class="btn btn-sm btn-outline-warning"><i class="far fa-edit"></i></a>&nbsp;&nbsp;';
                    echo '<a href="#" class="btn btn-icon btn-sm btn-outline-danger"><i class="far fa-trash-alt"></i></a></td>';
                }
            } else {
                echo '<td><a href="#" onclick="editDisposisi(' . $row['id_disposisi'] . ',' . "'" . $row['kepada'] . "',"  . $id_surat  . ') "title="Edit"class="btn btn-icon btn-sm btn-warning"><i class="far fa-edit"></i></a>&nbsp;&nbsp;';
                echo '<a href="#" onclick="hapusDisposisi(' . $row['id_disposisi'] . ') "title="Delete"class="btn btn-icon btn-sm btn-danger"><i class="far fa-trash-alt"></i></a></td>';
            }

            $i++;
        }
        // echo json_encode($disposisi);
    }

    public function store()
    {
        $disposisiModel = $this->disposisiModel;
        $suratMasukModel = $this->suratMasukModel;
        $disposisiPerUser = $this->disposisiPerUser;
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'kepada' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harus ada tujuan disposisi!'
                    ]
                ],
                'isi'           => 'required',
            ])) {
                $this->output['errors'] = $validation->getErrors();
                echo json_encode($this->output);
            } else {
                $dari = session()->get('jabatan');
                $id_surat = $this->request->getPost('id_surat');
                $kepada = $this->request->getPost('kepada');
                $isi = $this->request->getPost('isi');
                $data = [
                    'tanggal_disposisi'           => date('Y-m-d'),
                    'dari'  => $dari,
                    'kepada'        => implode(", ", $kepada),
                    'isi' => $isi,
                    'id_surat' => $id_surat
                ];
                $save = $disposisiModel->save($data);
                $id_disposisi = $disposisiModel->getInsertID();
                for ($i = 0; $i < count($kepada); $i++) {
                    $kepadaExp = $kepada[$i];
                    $dataDisposisi = [
                        'kepada' => $kepadaExp,
                        'status' => 'Belum di proses',
                        'id_surat' => $id_surat,
                        'id_disposisi' => $id_disposisi
                    ];
                    $disposisiPerUser->save($dataDisposisi);
                }
                if ($save) {
                    $this->output['success'] = true;
                    $this->output['message'] = 'Disposisi surat success.';
                }
                echo json_encode($this->output);
            }
        }
    }

    public function edit()
    {
        $disposisiModel = $this->disposisiModel;
        if ($this->request->isAJAX()) {
            $id_disposisi = $this->request->getVar('id_disposisi');
            $result = $disposisiModel->find($id_disposisi);
            if ($result) {
                $this->output['success'] = true;
                $this->output['message']  = 'Data ditemukan';
                $this->output['data']   = $result;
            }
            echo json_encode($this->output);
        }
    }
    public function update()
    {
        $disposisiModel = $this->disposisiModel;
        $disposisiPerUser = $this->disposisiPerUser;
        if ($this->request->isAJAX()) {
            $id_surat = $this->request->getPost('id_surat');
            $id_disposisi = $this->request->getPost('id_disposisi');
            $kepada = $this->request->getPost('kepada');
            $isi = $this->request->getPost('isi');
            $data_kepada = $this->request->getPost('id_kepada');
            $data = [
                'kepada'        => implode(", ", $kepada),
                'isi' => $isi,
            ];
            $update = $disposisiModel->update($id_disposisi, $data);
            $kepadaExp = explode(", ", $data_kepada);
            for ($i = 0; $i < count($kepadaExp); $i++) {
                if (!in_array($kepadaExp[$i], $kepada)) {
                    $disposisiPerUser->deleteData($kepadaExp[$i], $id_disposisi);
                }
            }
            for ($j = 0; $j < count($kepada); $j++) {
                if (!in_array($kepada[$j], $kepadaExp)) {
                    $dataDisposisi = [
                        'kepada' => $kepada[$j],
                        'status' => 'Belum di proses',
                        'id_surat' => $id_surat,
                        'id_disposisi' => $id_disposisi
                    ];
                    $disposisiPerUser->save($dataDisposisi);
                }
            }
            if ($update) {
                $this->output['success'] = true;
                $this->output['message'] = 'Record has been edited successfully.';
            }
            echo json_encode($this->output);
        }
    }
    public function getStatus($id_disposisi)
    {
        $disposisiModel = $this->disposisiModel;
        $disposisiPerUser = $this->disposisiPerUser;
        if ($this->request->isAJAX()) {
            $result = $disposisiPerUser->getStatus($id_disposisi);

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
        $disposisiPerUser = $this->disposisiPerUser;
        if ($this->request->isAJAX()) {
            // $data = [
            //     'status' => 'Sudah di proses'
            // ];
            $update = $disposisiPerUser->updateStatus($id_disposisi);
            if ($update) {
                $this->output['success'] = true;
                $this->output['message'] = 'Record has been updated successfully.';
            }
            echo json_encode($this->output);
        }
    }

    public function hapusDisposisi($id_disposisi)
    {
        $disposisiModel = $this->disposisiModel;
        if ($this->request->isAJAX()) {
            $delete = $disposisiModel->delete($id_disposisi);
            if ($delete) {
                $this->output['success'] = true;
                $this->output['message']  = 'Record has been removed successfully.';
            }
            echo json_encode($this->output);
        }
    }
}
