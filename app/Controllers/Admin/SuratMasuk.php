<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SuratMasukModel;
use App\Models\DisposisiModel;
use App\Models\Transaksisuratmasukmodel;

class SuratMasuk extends BaseController
{
    protected $suratMasukModel;
    protected $disposisiModel;
    protected $transaksiSuratMasukModel;
    public $output = [
        'success' => false,
        'message' => '',
        'data' => []
    ];

    public function __construct()
    {
        $this->suratMasukModel = new SuratMasukModel();
        $this->disposisiModel = new DisposisiModel();
        $this->transaksiSuratMasukModel = new Transaksisuratmasukmodel();
    }
    public function index()
    {
        $data = [
            'title' => 'Surat Masuk',
        ];
        return view('admin/suratmasuk', $data);
    }
    public function ajax_list()
    {
        $suratMasukModel = $this->suratMasukModel;
        $disposisiModel = $this->disposisiModel;
        $where = ['id_surat !=' => 0];
        $column_order = array('', 'tanggal_surat', 'no_surat', 'no_agenda', 'sifat_surat', 'pengirim', '', '');
        $column_search = array('no_surat', 'no_agenda', 'pengirim');
        $order = array('tanggal_surat' => 'DESC');
        $list = $suratMasukModel->get_datatables('surat_masuk', $column_order, $column_search, $order, $where);
        $data = array();
        $get_disposisi = true;
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $lists) {
            $no++;
            $row = array();
            $countDisposisi = $disposisiModel->countDisposisi($lists->id_surat);
            if ($countDisposisi == 0) {
                $disposisi = '<a href="#" onclick="disposisi(' . $lists->id_surat . ') "title="Belum di disposisi" class="btn btn-sm btn-outline-success"><i class="far fa-paper-plane"></i></a>';
            } else {
                $disposisi = '<a href="#profile" id="disposisi" onclick="detail(' . $lists->id_surat . ', ' . $get_disposisi . ') "title="Sudah di disposisi" class="btn btn-icon btn-sm btn-success"><i class="far fa-paper-plane"></i></a>';
            }
            $detail = '<a href="#" onclick="detail(' . $lists->id_surat . ') "title="Detail" class="btn btn-icon btn-sm btn-primary"><i class="far fa-eye"></i></a>';
            $edit = '<a href="#" onclick="edit(' . $lists->id_surat . ') "title="Edit" class="btn btn-icon btn-sm btn-warning"><i class="far fa-edit"></i></a>';
            $delete = '<a href="#" onclick="hapus(' . $lists->id_surat . ') "title="Delete"class="btn btn-icon btn-sm btn-danger"><i class="far fa-trash-alt"></i></a>';
            $row[] = $no;
            $row[] = date("d-m-Y", strtotime($lists->tanggal_surat));
            $row[] = $lists->no_surat;
            $row[] = $lists->no_agenda;
            $row[] = $lists->sifat_surat;
            $row[] = $lists->pengirim;
            $row[] = $disposisi;
            $row[] = $detail . '&nbsp;&nbsp;' . $edit . '&nbsp;&nbsp;' . $delete;
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

    public function generateNoSurat()
    {
        $transaksiSuratMasukModel = $this->transaksiSuratMasukModel;
        $query = $transaksiSuratMasukModel->getNomorSurat();
        $no = $query->nomor;
        $nomorUrut = $no + 1;
        $nomorAgenda = sprintf('%03s', $nomorUrut);
        if ($this->request->isAJAX()) {
            $this->output['success'] = true;
            $this->output['message']  = 'Data ditemukan';
            $this->output['data']   = $nomorAgenda;
            echo json_encode($this->output);
        }
    }
    //add data
    public function store()
    {
        $transaksiSuratMasukModel = $this->transaksiSuratMasukModel;
        $suratMasukModel = $this->suratMasukModel;
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'no_surat'           => 'required',
                'tgl_surat'  => 'required',
                'sifat_surat'        => 'required',
                'perihal'  => 'required',
                'no_agenda'  => 'required|is_unique[surat_masuk.no_agenda]',
                'tgl_diterima'  => 'required',
                'pengirim'  => 'required',
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
                $tgl_diterima = $this->request->getPost('tgl_diterima');
                $sifat_surat = $this->request->getPost('sifat_surat');
                $pengirim = $this->request->getPost('pengirim');
                $perihal = $this->request->getPost('perihal');
                $file = $this->request->getFile('userFile');
                $fileName = $file->getName();
                $file->move('uploads');
                $data = [
                    'no_surat'           => $no_surat,
                    'no_agenda'  => $no_agenda,
                    'tanggal_surat'        => $tgl_surat,
                    'tanggal_diterima'           => $tgl_diterima,
                    'sifat_surat'  => $sifat_surat,
                    'pengirim'        => $pengirim,
                    'perihal'           => $perihal,
                    'file'        => $fileName,
                    'created_by' => $user_id
                ];
                $save = $suratMasukModel->insert($data);
                $id_surat = $suratMasukModel->getInsertID();
                $transaksi = [
                    'kode_transaksi' => $no_agenda,
                    'tahun' => date('Y'),
                    'id_surat' => $id_surat,
                ];
                $transaksiSuratMasukModel->save($transaksi);
                if ($save) {
                    $this->output['success'] = true;
                    $this->output['message'] = 'Record has been added successfully.';
                }
                echo json_encode($this->output);
            }
        }
    }

    public function detail($id_surat)
    {
        $suratMasukModel = $this->suratMasukModel;
        if ($this->request->isAJAX()) {
            $result = $suratMasukModel->find($id_surat);
            if ($result) {
                $this->output['success'] = true;
                $this->output['message']  = 'Data ditemukan';
                $this->output['data']   = $result;
            }
            echo json_encode($this->output);
        }
    }

    public function edit()
    {
        $suratMasukModel = $this->suratMasukModel;
        if ($this->request->isAJAX()) {
            $id_surat = $this->request->getVar('id_surat');
            $result = $suratMasukModel->find($id_surat);
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
        $suratMasukModel = $this->suratMasukModel;
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'no_surat'           => 'required',
                'tgl_surat'  => 'required',
                'sifat_surat'        => 'required',
                'perihal'  => 'required',
                'no_agenda'  => 'required',
                'tgl_diterima'  => 'required',
                'pengirim'  => 'required',
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
                $tgl_diterima = $this->request->getPost('tgl_diterima');
                $sifat_surat = $this->request->getPost('sifat_surat');
                $pengirim = $this->request->getPost('pengirim');
                $perihal = $this->request->getPost('perihal');
                $file = $this->request->getFile('userFile');
                if ($file->getError() == 4) {
                    $fileName = $this->request->getPost('oldFile');
                } else {
                    $fileName = $file->getName();
                    $file->move('uploads');
                }

                $data = [
                    'no_surat'           => $no_surat,
                    'no_agenda'  => $no_agenda,
                    'tanggal_surat'        => $tgl_surat,
                    'tanggal_diterima'           => $tgl_diterima,
                    'sifat_surat'  => $sifat_surat,
                    'pengirim'        => $pengirim,
                    'perihal'           => $perihal,
                    'file'        => $fileName,
                    'updated_by' => $user_id
                ];
                $update = $suratMasukModel->update($id_surat, $data);
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
        $suratMasukModel = $this->suratMasukModel;
        $disposisiModel = $this->disposisiModel;
        if ($this->request->isAJAX()) {
            $delete = $suratMasukModel->delete($id_surat);
            $disposisiModel->where('id_surat', $id_surat)->delete(); //delete disposisi ketika surat di delete
            if ($delete) {
                $this->output['success'] = true;
                $this->output['message']  = 'Record has been removed successfully.';
            }
            echo json_encode($this->output);
        }
    }

    public function download($id_surat)
    {
        $suratMasukModel = $this->suratMasukModel;
        $data = $suratMasukModel->find($id_surat);
        return $this->response->download('uploads/' . $data['file'], null);
    }

    public function preview($id_surat)
    {
        $suratMasukModel = $this->suratMasukModel;
        $file = $suratMasukModel->find($id_surat);
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
