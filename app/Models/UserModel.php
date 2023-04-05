<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected $table            = 'users';
    protected $afterFind        = ['addDataToResult'];

    protected function initialize(): void
    {
        $this->allowedFields = [
            ...$this->allowedFields,
        ];
    }

    public function addDataToResult(array $data)
    {
        if (in_array($data['method'], ['find'])) {
            $user          = $data['data'];
            $user->nome    = $this->getExtraPropertyFromUser($user, 'nome');
            $user->email   = $this->getExtraPropertyFromUser($user, 'email');
        }

        if (in_array($data['method'], ['findAll'])) {
            foreach ($data['data'] as $user) {
                $user->nome    = $this->getExtraPropertyFromUser($user, 'nome');
                $user->email   = $this->getExtraPropertyFromUser($user, 'email');
            }
        }
        return $data;
    }

    public function getExtra(User $user, bool $all = true): string
    {
        $userIdentities = $user->getIdentities();

        return $userIdentities[0]->__get('extra');
    }

    /**
     * Retorna uma propriedade específica da informação extra do usuário
     *
     * @param User        $user     Informação crua vinda da ApiSig ou banco de dados para json_decode
     * @param string|null $property Qual o campo deseja retornar. Ex. nome|cpf_cnpj|email|login|tipo_usuario
     *
     */
    public function getExtraPropertyFromUser(User $user, string $property = null): ?string
    {
        $extra        = $this->getExtra($user);
        $userJson     = json_decode($extra, true);
        if (is_null($property)) {
            return $userJson;
        }
        if (isset($userJson[0][$property])) {
            return $userJson[0][$property];
        }
        return null;
    }
}
