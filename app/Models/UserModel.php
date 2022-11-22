<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    public $db;
    public $builder;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'fullname', 'level', 'jabatan'];


    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function cekLogin($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getProfil($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getUsers()
    {
        $users_id = session()->get('id');
        $this->where('id !=', $users_id);
        $this->where('jabatan !=', 'Admin(TU)');
        $this->where('jabatan !=', 'Kepala Sekolah');
        $this->orderBy('jabatan', 'ASC');
        return $this->find();
    }

    protected function _get_datatables_query($table, $column_order, $column_search, $order, $data = '')
    {

        $this->builder = $this->db->table($table);

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
            $this->builder->where($data);
        }
        $query = $this->builder->get();
        return $query->getResult();
    }

    public function count_filtered($table, $column_order, $column_search, $order, $data = '')
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $data);
        if ($data) {
            $this->builder->where($data);
        }
        return $this->builder->countAllResults();
    }

    public function count_all($table, $data = '')
    {
        $this->builder->select($table);
        $this->builder->where($data);
        return $this->builder->countAllResults();
    }
}
