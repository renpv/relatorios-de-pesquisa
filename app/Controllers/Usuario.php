<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Shield\Entities\UserIdentity;
use CodeIgniter\Shield\Models\UserIdentityModel;

class Usuario extends BaseController
{
    public function perfil()
    {
        $user          = auth()->getUser();
        $userIdentity  = new UserIdentityModel();
        /** @var UserIdentity|null */
        $userExtraJson = $userIdentity->find($user->id);
        // if (is_null($userExtraJson)) {
        //     log_message('critical', sprintf('O usuário [%s] não tem informação [extra]', $user->username));
        //     return redirect()->back()->with('danger', 'Erro ao tentar buscar as informações do usuário. Informe o problema à CPq.');
        // }

        if (!is_null($userExtraJson)) {
            $clearProfile  = $this->getFormatedExtra($userExtraJson);
        } else {
            $clearProfile = null;
        }

        return view('usuario/perfil', ['profile' => $clearProfile]);
    }

    /**
     * @param UserIdentity $extra
     */
    private function getFormatedExtra(UserIdentity $extra)
    {
        $extraInfo   = json_decode($extra->__get('extra'));
        $extraReturn = [];
        foreach ($extraInfo as $e) {
            unset($e->cpf_cnpj, $e->passaporte, $e->senha, $e->identidade, $e->id_pessoa, $e->id_usuario, $e->id_status_discente, $e->id_curso, $e->id_turno, $e->id_tipo_usuario,$e->id_servidor, $e->id_status_servidor, $e->id_categoria
            );
            if (!auth()->user()->inGroup('docente')) {
                unset($e->siape, $e->status_servidor, $e->categoria);
            }
            if (!auth()->user()->inGroup('discente')) {
                unset($e->matricula_disc, $e->nivel_discente, $e->nome_curso, $e->status_discente, $e->turno);
            }
            $extraReturn = $e;
        }
        return $extraReturn;
    }

    public function list()
    {
        $users = (new UserModel())->findAll();
        return view('usuario/list', compact('users'));
    }

    public function view($id)
    {
        $user = (new UserModel())->find($id);
        if (is_null($user)) {
            return redirect()->back()->with('danger', 'Usuário não encontrado na base de dados');
        }
        return view('usuario/view', compact('user'));
    }
    public function atualizar_grupos()
    {
        $grupos     = $this->request->getPost();
        /** @var \CodeIgniter\Shield\Entities\User|null */
        $user       = (new UserModel())->find($grupos['id']);
        if (is_null($user)) {
            return redirect()->back()->with('danger', 'Usuário não encontrado na base de dados');
        }

        $AuthGroups = ['superadmin', 'admin', 'clic'];
        foreach ($AuthGroups as $group) {
            if (in_array($group, $grupos)) {
                $user->addGroup($group);
            } else {
                $user->removeGroup($group);
            }
        }
        return redirect()->back()->with('success', 'Perfil de usuário atualizado com sucesso');
    }
}
