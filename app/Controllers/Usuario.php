<?php

namespace App\Controllers;

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
}
