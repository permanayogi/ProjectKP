<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }
    public function index()
    {
        helper(['form']);
        return view('login');
    }

    public function auth()
    {
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $data = $this->userModel->cekLogin($username);

        if ($data) {
            $pass = $data['password'];
            if ($pass == $password) {
                $ses_data = [
                    'id' => $data['id'],
                    'username' => $data['username'],
                    'fullname' => $data['fullname'],
                    'level' => $data['level'],
                    'jabatan' => $data['jabatan'],
                    'logged_in' => TRUE
                ];
                $this->session->set($ses_data);
                if ($this->session->get('level') == 'admin' || $this->session->get('level') == 'kepsek') {
                    return redirect()->to('/admin');
                } elseif ($this->session->get('level') == 'guru') {
                    return redirect()->to('/guru');
                }
            } else {
                session()->setFlashdata('loginfailed', 'Password salah!');
                return redirect()->to('/');
            }
        } else {
            session()->setFlashdata('loginfailed', 'Username salah!');
            return redirect()->to('/');
        }
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
