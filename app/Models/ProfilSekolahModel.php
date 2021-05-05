<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilSekolahModel extends Model
{
    protected $table = 'profil_sekolah';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_sekolah', 'alamat', 'provinsi', 'kecamatan'
    ];

    public function getData()
    {
        return $this->where(['id' => '1'])->first();
    }
}
