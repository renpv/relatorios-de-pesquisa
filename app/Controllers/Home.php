<?php

namespace App\Controllers;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
        return view('dashboard');
    }

    public function config()
    {
        $super_admin = getenv('SUPER_ADMIN');

        if (!$super_admin) {
            return redirect()->back()->with('info', 'Nenhum usuário configurado para Super Admin');
        }

        /** @var User|null */
        $super = (new UserModel())->where('username', $super_admin)->first();

        if (is_null($super)) {
            return redirect()->back()->with('info', 'Usuário configurado para Super Admin não existe no Banco de Dados');
        }

        if ($super->inGroup('superadmin')) {
            return redirect()->back()->with('info', sprintf('Usuário [%s] já está configurado como Superadmin no Banco de Dados', $super_admin));
        }

        $super->addGroup('superadmin');
        return redirect()->back()->with('success', sprintf('Usuário [%s] foi configurado como Superadmin no Banco de Dados com sucesso', $super_admin));
    }
}
