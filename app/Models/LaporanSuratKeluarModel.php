<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class Laporansuratkeluarmodel extends Model
{
    public $db;
    public $builder;
    protected $table = 'surat_keluar';
    protected $primaryKey = 'id_surat';

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    protected function _get_datatables_query($table, $column_order, $column_search, $order, $start_date, $end_date)
    {
        $this->builder = $this->db->table($table);
        $this->builder->where('tanggal_surat >=', $start_date);
        $this->builder->where('tanggal_surat <=', $end_date);

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

    public function get_datatables($table, $column_order, $column_search, $order, $start_date, $end_date)
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $start_date, $end_date);
        if (isset($_POST['length']) && $_POST['length'] != -1)
            $this->builder->limit($_POST['length'], $_POST['start']);

        $query = $this->builder->get();
        return $query->getResult();
    }

    public function count_filtered($table, $column_order, $column_search, $order, $start_date, $end_date)
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $start_date, $end_date);

        return $this->builder->countAllResults();
    }

    public function count_all($table, $start_date, $end_date)
    {
        $this->builder->select($table);
        $this->builder->where('tanggal_surat >=', $start_date);
        $this->builder->where('tanggal_surat <=', $end_date);
        return $this->builder->countAllResults();
    }

    public function getLaporan($start_date, $end_date)
    {
        $this->builder = $this->db->table('surat_keluar');
        $this->builder->where('tanggal_surat >=', $start_date);
        $this->builder->where('tanggal_surat <=', $end_date);
        $query = $this->builder->get();
        return $query->getResult();
    }
}
