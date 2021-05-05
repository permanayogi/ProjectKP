<?php

namespace App\Models;

use CodeIgniter\Model;

class DisposisiModel extends Model
{
    protected $table = 'disposisi';
    protected $primaryKey = 'id_disposisi';
    protected $allowedFields = [
        'id_disposisi', 'tanggal_disposisi', 'dari', 'kepada', 'isi', 'status', 'id_surat'
    ];

    public function getData($id_surat)
    {
        // $this->select('id_disposisi, tanggal_disposisi, users.jabatan AS "Dari", users.jabatan AS "Kepada", isi_disposisi');
        // $this->join('users', 'users.id=disposisi.dari');
        // $this->join('users', 'users.id=disposisi.kepada');
        // $this->where('disposisi.id =', $id_surat);
        $sql = "SELECT
                    id_disposisi,
                    tanggal_disposisi,
                    CONCAT( u.jabatan, ' - ', u.fullname ) AS 'Dari',
                    CONCAT( uk.jabatan, ' - ', uk.fullname ) AS 'Kepada',
                    isi 
                FROM
                    disposisi d
                    INNER JOIN users u ON u.id = d.dari
                    INNER JOIN users uk ON uk.id = d.kepada 
                WHERE
                    d.id_surat = $id_surat";
        $result = $this->query($sql);
        return $result->getResultArray();
    }

    public function getStatus($id_disposisi)
    {
        $userId = session()->get('id');
        $this->where(['kepada' => $userId]);
        $this->where(['id_disposisi' => $id_disposisi]);
        return $this->first();
    }
}
