<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $table = 'vw_dashboard';

    public function getData()
    {
        return $this->where(['id' => '1'])->first();
    }
}
