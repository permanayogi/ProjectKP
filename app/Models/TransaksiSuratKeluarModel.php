<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class Transaksisuratkeluarmodel extends Model
{
    public $db;
    public $builder;
    protected $table = 'transaksi_surat_keluar';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_transaksi', 'tahun', 'id_surat'
    ];
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getNomorSurat()
    {
        $sql = "SELECT
                     MAX(kode_transaksi) AS 'nomor'
                FROM 
                    transaksi_surat_keluar 
                WHERE 
                    YEAR(tahun) = YEAR(NOW())";
        $result = $this->query($sql);
        return $result->getRow();
    }
}
