<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;
    public $output = [
        'success' => false,
        'message' => '',
        'data' => []
    ];

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'User List',
        ];
        return view('admin/users', $data);
    }

    public function ajax_list()
    {
        $userModel = $this->userModel;
        $where = ['level !=' => 'admin'];
        $column_order = array('', 'username', 'fullname', 'level', 'jabatan', '');
        $column_search = array('username', 'fullname', 'jabatan');
        $order = array('id' => 'ASC');
        $list = $userModel->get_datatables('users', $column_order, $column_search, $order, $where);
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $lists) {
            $no++;
            $row = array();

            $edit = '<a href="#" onclick="edit(' . $lists->id . ') "title="Edit" class="btn btn-icon btn-sm btn-warning"><i class="far fa-edit"></i></a>';
            $delete = '<a href="#" onclick="hapus(' . $lists->id . ') "title="Delete"class="btn btn-icon btn-sm btn-danger"><i class="far fa-trash-alt"></i></a>';
            $row[] = $no;
            $row[] = $lists->username;
            $row[] = $lists->fullname;
            $row[] = $lists->level;
            $row[] = $lists->jabatan;
            $row[] = $edit . '&nbsp;&nbsp;' . $delete;
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : null,
            "recordsTotal" => $userModel->count_all('users', $where),
            "recordsFiltered" => $userModel->count_filtered('users', $column_order, $column_search, $order, $where),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function store()
    {
        $userModel = $this->userModel;
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'username'  => 'required',
                'nama'  => 'required',
                'jabatan'  => 'required'
            ])) {
                $this->output['errors'] = $validation->getErrors();
                echo json_encode($this->output);
            } else {
                $username = $this->request->getPost('username');
                $nama = $this->request->getPost('nama');
                $level = $this->request->getPost('level');
                $jabatan = $this->request->getPost('jabatan');
                $data = [
                    'username'  => $username,
                    'fullname'  => $nama,
                    'level'  => $level,
                    'jabatan'  => $jabatan
                ];
                $save = $userModel->save($data);
                if ($save) {
                    $this->output['success'] = true;
                    $this->output['message'] = 'Record has been added successfully.';
                }
                echo json_encode($this->output);
            }
        }
    }

    public function edit()
    {
        $id = $this->request->getVar('id');
        $userModel = $this->userModel;
        if ($this->request->isAJAX()) {
            $result = $userModel->find($id);
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
        $userModel = $this->userModel;
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'username'  => 'required',
                'nama'  => 'required',
                'jabatan'  => 'required'
            ])) {
                $this->output['errors'] = $validation->getErrors();
                echo json_encode($this->output);
            } else {
                $id = $this->request->getPost('id');
                $username = $this->request->getPost('username');
                $nama = $this->request->getPost('nama');
                $level = $this->request->getPost('level');
                $jabatan = $this->request->getPost('jabatan');

                $data = [
                    'username'  => $username,
                    'fullname'  => $nama,
                    'level'  => $level,
                    'jabatan'  => $jabatan
                ];
                $update = $userModel->update($id, $data);
                if ($update) {
                    $this->output['success'] = true;
                    $this->output['message'] = 'Record has been updated successfully.';
                }
                echo json_encode($this->output);
            }
        }
    }

    public function hapus($id)
    {
        $userModel = $this->userModel;
        if ($this->request->isAJAX()) {
            $delete = $userModel->delete($id);
            if ($delete) {
                $this->output['success'] = true;
                $this->output['message']  = 'Record has been removed successfully.';
            }
            echo json_encode($this->output);
        }
    }
}
