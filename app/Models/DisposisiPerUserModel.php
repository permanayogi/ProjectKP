<?php

namespace App\Models;

use CodeIgniter\Model;

class Disposisiperusermodel extends Model
{
    protected $table = 'disposisi_per_user';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kepada', 'status', 'id_surat', 'id_disposisi'
    ];
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function deleteData($kepada, $id_disposisi)
    {
        $sql = "DELETE
                FROM
                    disposisi_per_user
                WHERE
                    kepada = '$kepada'
                AND
                    id_disposisi = $id_disposisi";
        $this->query($sql);
    }

    public function getStatus($id_disposisi)
    {
        $userId = session()->get('jabatan');
        $this->where(['id_disposisi' => $id_disposisi]);
        $this->like('kepada', $userId);
        return $this->first();
    }

    public function updateStatus($id_disposisi)
    {
        $kepada = session()->get('jabatan');
        $sql = "UPDATE 
                    disposisi_per_user
                SET
                    `status` = 'Sudah di proses'
                WHERE
                    kepada = '$kepada'
                AND
                    id_disposisi = $id_disposisi";
        $this->query($sql);
        return true;
    }

    // public function insertData()
}
