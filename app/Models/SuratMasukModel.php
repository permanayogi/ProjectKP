<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class SuratMasukModel extends Model
{
    public $db;
    public $builder;
    protected $table = 'surat_masuk';
    protected $primaryKey = 'id_surat';
    protected $allowedFields = [
        'no_surat', 'no_agenda', 'tanggal_surat',
        'tanggal_diterima', 'sifat_surat', 'pengirim', 'perihal',  'file', 'disposisi', 'created_by', 'updated_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    protected function _get_datatables_query($table, $column_order, $column_search, $order, $data = '')
    {
        $this->builder = $this->db->table($table);
        // if (session()->get('level') != 'admin' && session()->get('level') != 'kepsek') {
        //     $this->builder->select($table . ".*, disposisi.id_disposisi, disposisi.status");
        //     $this->builder->join('disposisi', 'surat_masuk.id_surat = disposisi.id_surat');
        //     $this->builder->whereIn('disposisi.id_disposisi', function (BaseBuilder $builder) {
        //         return $builder->select('MAX(id_disposisi)', false)->from('disposisi')->where('kepada', session()->get('id'))->groupBy('id_surat');
        //     });
        // }
        // if (session()->get('level') != 'admin' && session()->get('level') != 'kepsek') {
        //     $this->builder->select($table . ".*, disposisi.id_disposisi, disposisi.status");
        //     $this->builder->join('disposisi', 'surat_masuk.id_surat = disposisi.id_surat');
        //     $this->builder->whereIn('disposisi.id_disposisi', function (BaseBuilder $builder) {
        //         return $builder->select('MAX(id_disposisi)', false)->from('disposisi')->like('kepada', session()->get('jabatan'))->groupBy('id_surat');
        //     });
        if (session()->get('level') != 'admin' && session()->get('level') != 'kepsek') {
            $this->builder->select($table . ".*, disposisi_per_user.id_disposisi, disposisi_per_user.status");
            $this->builder->join('disposisi_per_user', 'surat_masuk.id_surat = disposisi_per_user.id_surat');
            $this->builder->whereIn('disposisi_per_user.id', function (BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('disposisi_per_user')->like('kepada', session()->get('jabatan'))->groupBy('id_surat');
            });
        }

        $i = 0;
        foreach ($column_search as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {
                if ($i === 0) {
                    $this->builder->groupStart();
                    $this->builder->like($item, $_POST['search']['value']);
                } else {
                    $this->builder->orLike($item, $_POST['search']['value']);
                }
                if (count($column_search) - 1 == $i)
                    $this->builder->groupEnd();
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->builder->orderBy($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $order = $order;
            $this->builder->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables($table, $column_order, $column_search, $order, $data = '')
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $data);
        if (isset($_POST['length']) && $_POST['length'] != -1)
            $this->builder->limit($_POST['length'], $_POST['start']);

        if ($data) {
            if (session()->get('level') != 'admin' && session()->get('level') != 'kepsek') {
                $this->builder->like($data);
            } else {
                $this->builder->where($data);
            }
        }
        $query = $this->builder->get();
        return $query->getResult();
    }

    public function count_filtered($table, $column_order, $column_search, $order, $data = '')
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $data);
        if ($data) {
            $this->builder->where($data);
            $this->builder->groupBy("id_surat");
        }
        return $this->builder->countAllResults();
    }

    public function count_all($table, $data = '')
    {
        $this->builder->select($table . ".*");
        if (session()->get('level') != 'admin' && session()->get('level') != 'kepsek') {
            $this->builder->join('disposisi_per_user', 'surat_masuk.id_surat = disposisi_per_user.id_surat');
            $this->builder->where($data);
            $this->builder->groupBy("id_surat");
        }
        return $this->builder->countAllResults();
    }

    public function notifikasi()
    {
        $jabatan = session()->get('jabatan');
        $this->select("surat_masuk.*, disposisi_per_user.status");
        $this->join('disposisi_per_user', 'surat_masuk.id_surat = disposisi_per_user.id_surat');
        $this->like('disposisi_per_user.kepada', $jabatan);
        // $this->where(['disposisi.status' => 'Belum di proses']);
        $this->orderBy('tanggal_surat', 'DESC');
        $this->orderBy('status', 'ASC');
        $this->groupBy('id_surat');
        $this->limit(4);
        return $this->findAll();
    }

    public function countNotification()
    {
        $userId = session()->get('jabatan');
        $this->select("*");
        $this->join('disposisi_per_user', 'surat_masuk.id_surat = disposisi_per_user.id_surat');
        $this->where(['disposisi_per_user.status' => 'Belum di proses']);
        $this->like('disposisi_per_user.kepada', $userId);
        $this->limit(4);
        return $this->countAllResults();
    }
}
