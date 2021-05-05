<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profil extends BaseController
{
    protected $userModel;
    protected $id;
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->id = session()->get('id');
    }

    public function index()
    {
        if (session()->get('level') == 'admin' || session()->get('level') == 'kepsek') {
            $urlSuratMasuk = '/admin/suratmasuk';
        } else {
            $urlSuratMasuk = '/guru';
        }
        $data = [
            'title' => 'Profil',
            'data' => $this->userModel->getProfil($this->id),
            'urlSuratMasuk' => $urlSuratMasuk,
            'urlProfil' => '/profil'
        ];
        return view('pages/profil', $data);
    }

    public function updateProfil()
    {
        $userModel = $this->userModel;
        $validation =  \Config\Services::validation();
        if (!$this->validate([
            'username'  => 'required',
            'nama'  => 'required',
        ])) {
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to('/admin/profil');
        } else {
            $username = $this->request->getPost('username');
            $nama = $this->request->getPost('nama');
            $data = [
                'username'  => $username,
                'fullname'  => $nama,
            ];
            $userModel->update($this->id, $data);
            session()->setFlashdata('success', 'Profile berhasil diubah!');
            return redirect()->to('/admin/profil');
        }
    }

    public function changePassword()
    {
        $userModel = $this->userModel;
        $validation =  \Config\Services::validation();
        if (!$this->validate([
            'current_password'  => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password sekarang harus diisi!'
                ]
            ],
            'new_password'  =>  [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password baru harus diisi!'
                ]
            ],
            'repeat_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Harus ulangi password!',
                    'matches' => 'Password tidak cocok!'
                ]
            ]
        ])) {
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to('/admin/profil');
        } else {
            $data = $this->userModel->getProfil(session()->get('id'));
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');
            $repeatPassword = $this->request->getPost('repeat_password');
            $old_pass = $data['password'];
            if ($old_pass == $currentPassword) {
                $data = [
                    'password' => $newPassword
                ];
                $userModel->update($this->id, $data);
                session()->setFlashdata('success', 'Password berhasil diubah!');
                return redirect()->to('/admin/profil');
            } else {
                session()->setFlashdata('wrongpassword', 'Password sekarang salah!');
                return redirect()->to('/admin/profil');
            }
        }
    }
}
