<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SuratKeluarModel;

class SuratKeluar extends BaseController
{
    protected $suratKeluarModel;
    public $output = [
        'success' => false,
        'message' => '',
        'data' => []
    ];

    public function __construct()
    {
        $this->suratKeluarModel = new SuratKeluarModel;
    }

    public function index()
    {
        $data = [
            'title' => 'Surat Keluar',
            'urlSuratMasuk' => '/admin/suratmasuk',
            'urlProfil' => '/profil'
        ];
        return view('admin/suratkeluar', $data);
    }

    public function ajax_list()
    {
        $suratKeluarModel = $this->suratKeluarModel;
        $where = ['id_surat !=' => 0];
        $column_order = array('', 'tanggal_surat', 'no_surat', 'no_agenda', 'penerima', 'file', '');
        $column_search = array('no_surat', 'no_agenda', 'penerima');
        $order = array('tanggal_surat' => 'DESC');
        $list = $suratKeluarModel->get_datatables('surat_keluar', $column_order, $column_search, $order, $where);
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $lists) {
            $no++;
            $row = array();
            $file = '<a href="' . base_url() . '/admin/suratkeluar/preview/' . $lists->id_surat . '" target="_blank">File</a> ';
            $edit = '<a href="#" onclick="edit(' . $lists->id_surat . ') "title="Edit" class="btn btn-icon btn-sm btn-warning"><i class="far fa-edit"></i></a>';
            $delete = '<a href="#" onclick="hapus(' . $lists->id_surat . ') "title="Delete"class="btn btn-icon btn-sm btn-danger"><i class="far fa-trash-alt"></i></a>';
            $row[] = $no;
            $row[] = date("d-m-Y", strtotime($lists->tanggal_surat));
            $row[] = $lists->no_surat;
            $row[] = $lists->no_agenda;
            $row[] = $lists->penerima;
            $row[] = $file;
            $row[] = $edit . '&nbsp;&nbsp;' . $delete;
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : null,
            "recordsTotal" => $suratKeluarModel->count_all('surat_keluar', $where),
            "recordsFiltered" => $suratKeluarModel->count_filtered('surat_keluar', $column_order, $column_search, $order, $where),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function store()
    {
        $suratKeluarModel = $this->suratKeluarModel;
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'no_surat'  => 'required',
                'no_agenda' => 'required',
                'tgl_surat' => 'required',
                'penerima' => 'required',
                'userFile' => [
                    'rules' => 'uploaded[userFile]|mime_in[userFile,application/pdf,image/jpg,image/jpeg,image/png]|max_size[userFile,2048]',
                    'errors' => [
                        'uploaded' => 'Harus Ada File yang diupload',
                        'mime_in' => 'File Extention Harus Berupa pdf,jpg,jpeg,gif,png',
                        'max_size' => 'Ukuran File Maksimal 2 MB'
                    ]

                ]
            ])) {
                $this->output['errors'] = $validation->getErrors();
                echo json_encode($this->output);
            } else {
                $user_id = session()->get('id');
                $no_surat = $this->request->getPost('no_surat');
                $no_agenda = $this->request->getPost('no_agenda');
                $tgl_surat = $this->request->getPost('tgl_surat');
                $penerima = $this->request->getPost('penerima');
                $file = $this->request->getFile('userFile');
                $fileName = $file->getName();
                $file->move('uploads');
                $data = [
                    'no_surat' => $no_surat,
                    'no_agenda'  => $no_agenda,
                    'tanggal_surat' => $tgl_surat,
                    'penerima' => $penerima,
                    'file' => $fileName,
                    'created_by' => $user_id
                ];
                $save = $suratKeluarModel->save($data);
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
        $suratKeluarModel = $this->suratKeluarModel;
        if ($this->request->isAJAX()) {
            $id_surat = $this->request->getVar('id_surat');
            $result = $suratKeluarModel->find($id_surat);
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
        $suratKeluarModel = $this->suratKeluarModel;
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'no_surat'  => 'required',
                'no_agenda' => 'required',
                'tgl_surat' => 'required',
                'penerima' => 'required',
                'userFile' => [
                    'rules' => 'mime_in[userFile,application/pdf,image/jpg,image/jpeg,image/png]|max_size[userFile,2048]',
                    'errors' => [
                        'mime_in' => 'File Extention Harus Berupa pdf,jpg,jpeg,gif,png',
                        'max_size' => 'Ukuran File Maksimal 2 MB'
                    ]
                ]
            ])) {
                $this->output['errors'] = $validation->getErrors();
                echo json_encode($this->output);
            } else {
                $user_id = session()->get('id');
                $id_surat = $this->request->getPost('id_surat');
                $no_surat = $this->request->getPost('no_surat');
                $no_agenda = $this->request->getPost('no_agenda');
                $tgl_surat = $this->request->getPost('tgl_surat');
                $penerima = $this->request->getPost('penerima');
                $file = $this->request->getFile('userFile');
                if ($file->getError() == 4) {
                    $fileName = $this->request->getPost('oldFile');
                } else {
                    $fileName = $file->getName();
                    $file->move('uploads');
                }
                $data = [
                    'no_surat' => $no_surat,
                    'no_agenda'  => $no_agenda,
                    'tanggal_surat' => $tgl_surat,
                    'penerima' => $penerima,
                    'file' => $fileName,
                    'updated_by' => $user_id
                ];
                $update = $suratKeluarModel->update($id_surat, $data);
                if ($update) {
                    $this->output['success'] = true;
                    $this->output['message'] = 'Record has been edited successfully.';
                }
                echo json_encode($this->output);
            }
        }
    }

    public function hapus($id_surat)
    {
        $suratKeluarModel = $this->suratKeluarModel;
        if ($this->request->isAJAX()) {
            $delete = $suratKeluarModel->delete($id_surat);
            if ($delete) {
                $this->output['success'] = true;
                $this->output['message']  = 'Record has been removed successfully.';
            }
            echo json_encode($this->output);
        }
    }

    public function preview($id_surat)
    {
        $suratKeluarModel = $this->suratKeluarModel;
        $file = $suratKeluarModel->find($id_surat);
        $getFileName = $file['file'];
        $explode = explode('.', $getFileName);
        $extension = strtolower(end($explode));
        $data = [
            'title' => $file['file'],
            'file' => base_url() . '/uploads/' . $getFileName,
            'extension' => $extension
        ];
        return view('pages/preview', $data);
    }
}
