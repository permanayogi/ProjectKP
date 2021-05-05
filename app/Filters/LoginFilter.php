<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = NULL)
    {
        if (session()->get('logged_in') == FALSE) // saya hanya membuat sederhana saja. silahkan kembangkan di kemudian hari
        {
            return redirect()->to('/');
        }
        // Do something here
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = NULL)
    {
        // Do something here
    }
}
