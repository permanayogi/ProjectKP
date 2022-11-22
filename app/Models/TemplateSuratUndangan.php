<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class Templatesuratundangan extends Model
{
    public $db;
    public $builder;
    protected $table = 'template_surat_undangan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'hari', 'jam', 'tempat', 'id_surat_keluar'
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
