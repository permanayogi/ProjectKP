<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DashboardModel;
use App\Models\ProfilSekolahModel;

class Dashboard extends BaseController
{
    protected $profilModel;
    protected $profilSekolahModel;

    public function __construct()
    {
        $this->profilModel = new DashboardModel();
        $this->userModel = new UserModel();
        $this->profilSekolahModel = new ProfilSekolahModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'count' => $this->profilModel->getData(), //profil sekolah
            'data' => $this->profilSekolahModel->getData(),
        ];
        return view('admin/dashboard', $data);
    }
    public function editProfilSekolah()
    {
        if ($this->request->isAJAX()) {
            $result = $this->profilSekolahModel->getData();
            if ($result) {
                $this->output['success'] = true;
                $this->output['message']  = 'Data ditemukan';
                $this->output['data']   = $result;
            }
            echo json_encode($this->output);
        }
    }

    public function updateProfilSekolah()
    {
        $validation =  \Config\Services::validation();
        if (!$this->validate([
            'nama_sekolah'  => 'required',
            'alamat_sekolah'  => 'required',
            'provinsi'  => 'required',
            'kecamatan'  => 'required',
            'no_telp'  => 'required',
            'kode_pos'  => 'required',
        ])) {
            session()->setFlashdata('errors', $validation->getErrors());
            return $this->index();
        } else {
            $profilSekolahModel = $this->profilSekolahModel;
            // if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $nama_sekolah = $this->request->getPost('nama_sekolah');
            $alamat = $this->request->getPost('alamat_sekolah');
            $provinsi = $this->request->getPost('provinsi');
            $kecamatan = $this->request->getPost('kecamatan');
            $no_telp = $this->request->getPost('no_telp');
            $kode_pos = $this->request->getPost('kode_pos');
            $data = [
                'nama_sekolah' => $nama_sekolah,
                'alamat_sekolah' => $alamat,
                'provinsi' => $provinsi,
                'kecamatan' => $kecamatan,
                'telpon' => $no_telp,
                'kode_pos' => $kode_pos
            ];
            $profilSekolahModel->update($id, $data);
            session()->setFlashdata('success', 'Data berhasil diubah!');
            return $this->index();
        }
    }

    public function getUsers()
    {
        $getUsers = $this->userModel->getUsers();
        echo json_encode($getUsers);
    }
}
