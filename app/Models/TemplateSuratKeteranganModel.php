<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class Templatesuratketeranganmodel extends Model
{
    public $db;
    public $builder;
    protected $table = 'template_surat_keterangan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_siswa', 'nisn', 'ttl', 'kelas', 'orang_tua', 'alamat', 'id_surat_keluar'
    ];
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getData($id_surat)
    {
        $this->where('id_surat_keluar =', $id_surat);
        return $this->first();
    }

    public function updateData($id_surat, $data)
    {
        $this->db->table($this->table)->update($data, ['id_surat_keluar' => $id_surat]);
    }
}
