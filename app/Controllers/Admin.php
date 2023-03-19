<?php

namespace App\Controllers;

use App\Libraries\ApiLoginSig;
use CodeIgniter\Shield\Models\UserModel;

class Admin extends BaseController
{
    public const HOME_ADMIN = 'admin/painel';
    public function index()
    {
        if (auth()->id()) {
            return redirect()->to(self::HOME_ADMIN);
        }
        return view('admin/login/index');
    }

    public function create()
    {
        $credentials = $this->request->getPost();

        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'login'    => 'required|min_length[6]',
                'password' => 'required|min_length[6]',
            ],
            [
                'login' => [
                    'required'   => 'O campo Login é requerido para avançar',
                    'min_length' => 'O campo Login deve ter pelo menos 6 caracteres',
                ],
                'password' => [
                    'required'   => 'O campo Senha é requerido para avançar',
                    'min_length' => 'O campo Senha deve ter pelo menos 6 caracteres',
                ],
            ]
        );

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('danger', $validation->getErrors());
        }

        $apiLoginSig = (new ApiLoginSig())->validate($credentials['login'], $credentials['password']);

        if ($apiLoginSig['error']) {
            return redirect()->back()->with('danger', $apiLoginSig['data']);
        }

        $userModel = new UserModel();
        $userFind  = $userModel->where('username', $credentials['login'])->first();

        if (is_null($userFind)) {
            try {
                $idUser = $userModel->insert([
                    'username' => $credentials['login'],
                ]);
            } catch (\Throwable $th) {
                log_message('error', sprintf('Houve um problema ao tentar o usuário %s no banco de dados ', $credentials['login']));
                return redirect()->back()->with('danger', 'Problema ao tentar registrar no banco de dados. A equipe da DTI já foi comunicada.');
            }
        } else {
            $idUser = $userFind->id;
        }

        try {
            auth()->loginById($idUser);
            redirect()->to(self::HOME_ADMIN);
        } catch (\Throwable $th) {
            log_message('error', sprintf('Houve um problema ao tentar o logar com o usuário %s ', $credentials['login']));
            return redirect()->back()->with('danger', 'Problema ao tentar logar. A equipe da DTI já foi comunicada.');
        }

        log_message('warning', sprintf('Houve um problema ao tentar realizar login com o usuário %s', $credentials['login']));
        return redirect()->back()->with('info', 'Houve algum problema ao tentar realizar login');
    }

    public function logout()
    {
        auth()->logout();
    }

    public function painel()
    {
        return view(self::HOME_ADMIN);
    }
}
