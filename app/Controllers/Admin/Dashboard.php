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
            'urlSuratMasuk' => '/admin/suratmasuk',
            'urlProfil' => '/profil' //profil akun
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

    public function updateProfilSekolah() {
        
    }

    public function getUsers()
    {
        $getUsers = $this->userModel->getUsers();
        echo json_encode($getUsers);
    }
}
