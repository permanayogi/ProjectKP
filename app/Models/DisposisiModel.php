<?php

namespace App\Models;

use CodeIgniter\Model;

class Disposisimodel extends Model
{
    protected $table = 'disposisi';
    protected $primaryKey = 'id_disposisi';
    protected $allowedFields = [
        'id_disposisi', 'tanggal_disposisi', 'dari', 'kepada', 'isi', 'status', 'id_surat'
    ];

    public function getData($id_surat)
    {
        $sql = "SELECT
                    id_disposisi,
                    tanggal_disposisi,
                    dari,
                    kepada,
                    isi
                FROM
                    disposisi
                WHERE
                    id_surat = $id_surat";
        $result = $this->query($sql);
        return $result->getResultArray();
    }

    public function getStatus($id_disposisi)
    {
        $userId = session()->get('jabatan');
        $this->where(['id_disposisi' => $id_disposisi]);
        $this->like('kepada', $userId);
        return $this->first();
    }
    public function countDisposisi($id_surat)
    {
        $this->select("*");
        $this->where(['id_surat' => $id_surat]);
        return $this->countAllResults();
    }

    public function getLaporan($id_surat)
    {
        // $sql = "SELECT
        //             id_disposisi,
        //             id_surat,
        //             tanggal_disposisi,
        //             d.dari AS 'id_dari',
        //             u.fullname AS 'Dari',
        //             uk.fullname AS 'Kepada',
        //             isi 
        //         FROM
        //             disposisi d
        //             INNER JOIN users u ON u.id = d.dari
        //             INNER JOIN users uk ON uk.id = d.kepada 
        //        WHERE d.id_surat = $id_surat";
        $sql = "SELECT
                    id_disposisi,
                    tanggal_disposisi,
                    dari,
                    kepada,
                    isi
                FROM
                    disposisi
                WHERE
                    id_surat = $id_surat";
        $result = $this->query($sql);
        return $result->getResultArray();
    }
}
