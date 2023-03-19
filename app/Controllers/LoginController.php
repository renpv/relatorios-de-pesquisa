<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Models\UserModel;
use Config\Services;

class LoginController extends ShieldLogin
{
    public function loginAction(): RedirectResponse
    {
        $rules = $this->getValidationRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentials             = $this->request->getPost(setting('Auth.validFields'));
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getPost('password');
        $remember                = (bool) $this->request->getPost('remember');

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $loginSig = $this->loginApi($credentials);
        if ($loginSig['error']) {
            return redirect()->route('login')->withInput()->with('danger', $loginSig['data']);
        }

        return $this->userValidate($loginSig['data']);
    }

    /**
     * Returns the rules that should be used for validation.
     *
     * @return array<string, array<string, array<string>|string>>
     * @phpstan-return array<string, array<string, string|list<string>>>
     */
    protected function getValidationRules(): array
    {
        return setting('Validation.login') ?? [
            'username' => [
                'label' => 'Auth.username',
                'rules' => config('AuthSession')->usernameValidationRules,
            ],
            'password' => [
                'label'  => 'Auth.password',
                'rules'  => 'required',
            ],
        ];
    }

    private function loginApi($credentials): array
    {
        $apiLogin    = Services::apiLogin();
        $apiLoginSig = $apiLogin->validate($credentials['username'], $credentials['password']);
        return $apiLoginSig;
    }

    private function userValidate(array $user): ?RedirectResponse
    {
        $userModel = new UserModel();
        $userFind  = $userModel->where('username', $user[0]->login)->first();
        $isTeacher = false;
        $isStudent = false;

        if (!is_null($userFind)) {
            $idUser = $userFind->id;
        } else {
            try {
                $userEntity = new User([
                    'username' => $user[0]->login,
                    'email'    => $user[0]->email,
                ]);
                $userModel->save($userEntity);
                $idUser = $userModel->getInsertID();

                $userIdentity = new UserIdentityModel();
                $userIdentity->update($idUser, ['extra' => json_encode($user, JSON_FORCE_OBJECT)]);

                $isTeacher = $this->checkUserIsTeacher($user);
                $isStudent = $this->checkUserIsStudent($user);
            } catch (\Throwable $th) {
                log_message('error', sprintf('Houve um problema ao tentar o usuário %s no banco de dados ', $user[0]->login));

                return redirect()->back()->with('danger', 'Problema ao tentar registrar no banco de dados. A equipe da DTI já foi comunicada.');
            }
        }

        try {
            auth()->loginById($idUser);
            if ($isTeacher) {
                auth()->user()->addGroup('docente');
            }
            if ($isStudent) {
                auth()->user()->addGroup('discente');
            }
            session()->push('user', ['cpf' => $this->formatCPF($user[0]->cpf_cnpj)]);
            return redirect()->to(config('Auth')->loginRedirect())->withCookies();
        } catch (\Throwable $th) {
            log_message('error', sprintf('Houve um problema ao tentar o logar com o usuário %s ', $user[0]->login));
            return redirect()->back()->with('danger', 'Problema ao tentar logar. A equipe da DTI já foi comunicada.');
        }
    }

    private function checkUserIsTeacher(array $user): bool
    {
        foreach ($user as $profile) {
            if ($profile->id_tipo_usuario == 1 && $profile->id_categoria == 1) {
                return true;
            }
        }

        return false;
    }

    private function checkUserIsStudent(array $user): bool
    {
        foreach ($user as $profile) {
            if (!is_null($profile->id_status_discente)) {
                return true;
            }
        }

        return false;
    }

    private function formatCPF(int $cpf)
    {
        $cpf_txt = strval($cpf);
        $cpf_pad = str_pad($cpf_txt, 11, '0', STR_PAD_LEFT);
        return $cpf_pad;
    }
}
