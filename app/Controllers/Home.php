<?php

namespace App\Controllers;
use App\Interfaces\ApiLoginInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }
}
