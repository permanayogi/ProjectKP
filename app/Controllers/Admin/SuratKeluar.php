<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SuratKeluarModel;
use App\Models\Transaksisuratkeluarmodel;
use App\Models\Templatesuratketeranganmodel;
use App\Models\Templatesuratundangan;

class SuratKeluar extends BaseController
{
    protected $suratKeluarModel;
    protected $transaksiSuratKeluarModel;
    protected $templateSuratKeterangan;
    protected $templateSuratUndangan;
    public $output = [
        'success' => false,
        'message' => '',
        'data' => []
    ];

    public function __construct()
    {
        $this->suratKeluarModel = new SuratKeluarModel;
        $this->transaksiSuratKeluarModel = new Transaksisuratkeluarmodel;
        $this->templateSuratKeterangan = new Templatesuratketeranganmodel;
        $this->templateSuratUndangan = new Templatesuratundangan;
    }

    public function index()
    {
        $data = [
            'title' => 'Surat Keluar',
        ];
        return view('admin/suratkeluar', $data);
    }

    public function ajax_list()
    {
        $suratKeluarModel = $this->suratKeluarModel;
        $where = ['id_surat !=' => 0];
        $column_order = array('', 'tanggal_surat', 'no_surat', 'no_agenda', 'tipe',  '');
        $column_search = array('no_surat', 'no_agenda', 'tipe');
        $order = array('tanggal_surat' => 'DESC');
        $list = $suratKeluarModel->get_datatables('surat_keluar', $column_order, $column_search, $order, $where);
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $lists) {
            $no++;
            $row = array();
            if ($lists->tipe == 'Upload') {
                $cetak = '<a href="' . base_url() . '/admin/suratkeluar/preview/' . $lists->id_surat . '" target="_blank" title="Cetak" class="btn btn-icon btn-sm btn-info"><i class="fas fa-print"></i></a>';
            } else if ($lists->tipe == 'Surat Keterangan Siswa') {
                $cetak = '<a href="/suratketerangan?id_surat=' . $lists->id_surat . '" target="_blank" title="Cetak" class="btn btn-icon btn-sm btn-info"><i class="fas fa-print"></i></a>';
            } else {
                $cetak = '<a href="/suratundangan?id_surat=' . $lists->id_surat . '" target="_blank" title="Cetak" class="btn btn-icon btn-sm btn-info"><i class="fas fa-print"></i></a>';
            }
            $detail = '<a href="#" onclick="detail(' . $lists->id_surat . ') "title="Detail" class="btn btn-icon btn-sm btn-primary"><i class="far fa-eye"></i></a>';
            $edit = '<a href="#" onclick="edit(' . $lists->id_surat . ', ' . "'" . $lists->tipe . "'" . ') "title="Edit" class="btn btn-icon btn-sm btn-warning"><i class="far fa-edit"></i></a>';
            $delete = '<a href="#" onclick="hapus(' . $lists->id_surat . ') "title="Delete"class="btn btn-icon btn-sm btn-danger"><i class="far fa-trash-alt"></i></a>';
            $row[] = $no;
            $row[] = date("d-m-Y", strtotime($lists->tanggal_surat));
            $row[] = $lists->no_surat;
            $row[] = $lists->no_agenda;
            $row[] = $lists->tipe;
            $row[] = $cetak . '&nbsp;&nbsp;' . $detail . '&nbsp;&nbsp;' . $edit . '&nbsp;&nbsp;' . $delete;
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

    public function detail($id_surat)
    {
        $suratKeluarModel = $this->suratKeluarModel;
        $templateSuratKeterangan = $this->templateSuratKeterangan;
        $templateSuratUndangan = $this->templateSuratUndangan;
        if ($this->request->isAJAX()) {
            $result = $suratKeluarModel->find($id_surat);
            $dataSuratKeterangan = $templateSuratKeterangan->getData($id_surat);
            $dataSuratUndangan = $templateSuratUndangan->getData($id_surat);
            if ($result) {
                $this->output['success'] = true;
                $this->output['message']  = 'Data ditemukan';
                $this->output['data']   = $result;
                $this->output['dataSuratKeterangan'] = $dataSuratKeterangan;
                $this->output['dataSuratUndangan'] = $dataSuratUndangan;
            }
            echo json_encode($this->output);
        }
    }

    public function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public function generateNoSurat()
    {
        $transaksiSuratKeluarModel = $this->transaksiSuratKeluarModel;
        $bulan = date('n');
        $romawi = $this->getRomawi($bulan);
        $tahun = date('Y');
        $nomor = '/420/SMAN.1.TJB/' . $romawi . '/' . $tahun;
        $query = $transaksiSuratKeluarModel->getNomorSurat();
        $no = $query->nomor;
        $noUrut = $no + 1;
        $noAgenda = sprintf('%03s', $noUrut);
        $kode = sprintf('%03s', $noUrut);
        $nomorSuratNew = $kode . $nomor;
        if ($this->request->isAJAX()) {
            $this->output['success'] = true;
            $this->output['message']  = 'Data ditemukan';
            $this->output['data']   = $nomorSuratNew;
            $this->output['no_agenda']   = $noAgenda;
            echo json_encode($this->output);
        }
    }

    public function store()
    {
        $suratKeluarModel = $this->suratKeluarModel;
        $transaksiSuratKeluarModel = $this->transaksiSuratKeluarModel;
        $templateSuratKeterangan = $this->templateSuratKeterangan;
        $templateSuratUndangan = $this->templateSuratUndangan;
        $tipeSurat = $this->request->getPost('tipeSurat');
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if ($tipeSurat == null) {
                $input = $this->validate([
                    'tipeSurat' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Tipe surat harus dipilih!'
                        ]
                    ],
                ]);
            } else if ($tipeSurat == 'Uploads') {
                $input = $this->validate([
                    'no_surat'  => 'required|is_unique[surat_keluar.no_surat]',
                    'no_agenda' => 'required|is_unique[surat_keluar.no_agenda]',
                    'tgl_surat' => 'required',
                    'kepada' => 'required',
                    'perihal' => 'required',
                    'tipeSurat' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Tipe surat harus dipilih!'
                        ]
                    ],
                    'userFile' => [
                        'rules' => 'uploaded[userFile]|mime_in[userFile,application/pdf,image/jpg,image/jpeg,image/png]|max_size[userFile,2048]',
                        'errors' => [
                            'uploaded' => 'Harus Ada File yang diupload',
                            'mime_in' => 'File Extention Harus Berupa pdf,jpg,jpeg,gif,png',
                            'max_size' => 'Ukuran File Maksimal 2 MB'
                        ]
                    ]
                ]);
                $tipe = 'Upload';
            } else if ($tipeSurat == 'Surat Keterangan Siswa') {
                $input = $this->validate([
                    'tgl_surat' => 'required',
                    'nama_siswa'  => 'required',
                    'nisn' => 'required',
                    'ttl' => 'required',
                    'kelas' => 'required',
                    'orang_tua' => 'required',
                    'alamat' => 'required',
                    'tipeSurat' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Tipe surat harus dipilih!'
                        ]
                    ],
                ]);
                $tipe = 'Surat Keterangan Siswa';
            } else if ($tipeSurat == 'Surat Undangan') {
                $input = $this->validate([
                    'tgl_surat' => 'required',
                    'kepada' => 'required',
                    'hari' => 'required',
                    'jam' => 'required',
                    'tempat' => 'required',
                ]);
                $tipe = 'Surat Undangan';
            }
            if (!$input) {
                $this->output['errors'] = $validation->getErrors();
                echo json_encode($this->output);
            } else {
                $user_id = session()->get('id');
                $no_surat = $this->request->getPost('no_surat');
                $no_agenda = $this->request->getPost('no_agenda');
                $tgl_surat = $this->request->getPost('tgl_surat');
                $kepada = $this->request->getPost('kepada');
                $perihal = $this->request->getPost('perihal');
                $file = $this->request->getFile('userFile');
                $nama_siswa = $this->request->getPost('nama_siswa');
                $nisn = $this->request->getPost('nisn');
                $ttl = $this->request->getPost('ttl');
                $kelas = $this->request->getPost('kelas');
                $orang_tua = $this->request->getPost('orang_tua');
                $alamat = $this->request->getPost('alamat');
                $hari = $this->request->getPost('hari');
                $jam = $this->request->getPost('jam');
                $tempat = $this->request->getPost('tempat');
                if ($file == null) {
                    $fileName = '';
                } else {
                    $fileName = $file->getName();
                    $file->move('uploads');
                }
                $data = [
                    'no_surat' => $no_surat,
                    'no_agenda'  => $no_agenda,
                    'tanggal_surat' => $tgl_surat,
                    'kepada' => $kepada,
                    'perihal' => $perihal,
                    'file' => $fileName,
                    'tipe' => $tipe,
                    'created_by' => $user_id
                ];
                $save = $suratKeluarModel->ignore(true)->insert($data);
                $id_surat = $suratKeluarModel->getInsertID();
                $transaksi = [
                    'kode_transaksi' => $no_agenda,
                    'tahun' => date('Y'),
                    'id_surat' => $id_surat,
                ];
                $transaksiSuratKeluarModel->save($transaksi);
                if ($tipeSurat == 'Surat Keterangan Siswa') {
                    $suratKeterangan = [
                        'nama_siswa' => $nama_siswa,
                        'nisn' => $nisn,
                        'ttl' => $ttl,
                        'kelas' => $kelas,
                        'orang_tua' => $orang_tua,
                        'alamat' => $alamat,
                        'id_surat_keluar' => $id_surat,
                    ];
                    $templateSuratKeterangan->save($suratKeterangan);
                } else if ($tipeSurat == 'Surat Undangan') {
                    $suratUndangan = [
                        'hari' => $hari,
                        'jam' => $jam,
                        'tempat' => $tempat,
                        'id_surat_keluar' => $id_surat,
                    ];
                    $templateSuratUndangan->save($suratUndangan);
                }
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
            if ($result['tipe'] == 'Surat Undangan') {
                $tipeSurat = $this->templateSuratUndangan->getData($id_surat);
            } else if ($result['tipe'] == 'Surat Keterangan Siswa') {
                $tipeSurat = $this->templateSuratKeterangan->getData($id_surat);
            } else {
                $tipeSurat = 'Upload';
            }
            if ($result) {
                $this->output['success'] = true;
                $this->output['message']  = 'Data ditemukan';
                $this->output['data']   = $result;
                $this->output['tipe'] = $tipeSurat;
            }
            echo json_encode($this->output);
        }
    }

    public function update()
    {
        $suratKeluarModel = $this->suratKeluarModel;
        $templateSuratKeterangan = $this->templateSuratKeterangan;
        $templateSuratUndangan = $this->templateSuratUndangan;
        $tipeSurat = $this->request->getPost('tipeSurat');
        if ($this->request->isAJAX()) {
            $validation =  \Config\Services::validation();
            if ($tipeSurat == null) {
                $input = $this->validate([
                    'tipeSurat' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Tipe surat harus dipilih!'
                        ]
                    ],
                ]);
            } else if ($tipeSurat == 'Upload') {
                $input = $this->validate([
                    'no_surat'  => 'required',
                    'no_agenda' => 'required',
                    'tgl_surat' => 'required',
                    'kepada' => 'required',
                    'perihal' => 'required',
                    'tipeSurat' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Tipe surat harus dipilih!'
                        ]
                    ],
                    'userFile' => [
                        'rules' => 'mime_in[userFile,application/pdf,image/jpg,image/jpeg,image/png]|max_size[userFile,2048]',
                        'errors' => [
                            'mime_in' => 'File Extention Harus Berupa pdf,jpg,jpeg,gif,png',
                            'max_size' => 'Ukuran File Maksimal 2 MB'
                        ]
                    ]
                ]);
                $tipe = 'Upload';
            } else if ($tipeSurat == 'Surat Keterangan Siswa') {
                $input = $this->validate([
                    'tgl_surat' => 'required',
                    'nama_siswa'  => 'required',
                    'nisn' => 'required',
                    'ttl' => 'required',
                    'kelas' => 'required',
                    'orang_tua' => 'required',
                    'alamat' => 'required',
                    'tipeSurat' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Tipe surat harus dipilih!'
                        ]
                    ],
                ]);
                $tipe = 'Surat Keterangan Siswa';
            } else if ($tipeSurat == 'Surat Undangan') {
                $input = $this->validate([
                    'tgl_surat' => 'required',
                    'kepada' => 'required',
                    'hari' => 'required',
                    'jam' => 'required',
                    'tempat' => 'required',
                ]);
                $tipe = 'Surat Undangan';
            }
            if (!$input) {
                $this->output['errors'] = $validation->getErrors();
                echo json_encode($this->output);
            } else {
                $user_id = session()->get('id');
                $id_surat = $this->request->getPost('id_surat');
                $no_surat = $this->request->getPost('no_surat');
                $no_agenda = $this->request->getPost('no_agenda');
                $tgl_surat = $this->request->getPost('tgl_surat');
                $kepada = $this->request->getPost('kepada');
                $perihal = $this->request->getPost('perihal');
                $file = $this->request->getFile('userFile');
                $nama_siswa = $this->request->getPost('nama_siswa');
                $nisn = $this->request->getPost('nisn');
                $ttl = $this->request->getPost('ttl');
                $kelas = $this->request->getPost('kelas');
                $orang_tua = $this->request->getPost('orang_tua');
                $alamat = $this->request->getPost('alamat');
                $hari = $this->request->getPost('hari');
                $jam = $this->request->getPost('jam');
                $tempat = $this->request->getPost('tempat');
                if ($file == null) {
                    $fileName = '';
                } else if ($file->getError() == 4) {
                    $fileName = $this->request->getPost('oldFile');
                } else {
                    $fileName = $file->getName();
                    $file->move('uploads');
                }
                $data = [
                    'no_surat' => $no_surat,
                    'no_agenda'  => $no_agenda,
                    'tanggal_surat' => $tgl_surat,
                    'kepada' => $kepada,
                    'perihal' => $perihal,
                    'file' => $fileName,
                    'tipe' => $tipe,
                    'updated_by' => $user_id
                ];
                $update = $suratKeluarModel->update($id_surat, $data);
                if ($tipeSurat == 'Surat Keterangan Siswa') {
                    $suratKeterangan = [
                        'nama_siswa' => $nama_siswa,
                        'nisn' => $nisn,
                        'ttl' => $ttl,
                        'kelas' => $kelas,
                        'orang_tua' => $orang_tua,
                        'alamat' => $alamat,
                    ];
                    $templateSuratKeterangan->updateData($id_surat, $suratKeterangan);
                } else if ($tipeSurat == 'Surat Undangan') {
                    $suratUndangan = [
                        'hari' => $hari,
                        'jam' => $jam,
                        'tempat' => $tempat,
                        'id_surat_keluar' => $id_surat,
                    ];
                    $templateSuratUndangan->updateData($id_surat, $suratUndangan);
                }
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
