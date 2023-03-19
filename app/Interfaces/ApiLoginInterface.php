<?php

namespace App\Interfaces;

/**
 * Interface base para auxiliar no processo de validação de login via API externa
 *
 * Modo de usar:
 *
 * 1 - A classe que implementar essa API deve ser chamada com o seguinte código:
 * (new ApiLogin)->validate($login, $password);
 *
 */

interface ApiLoginInterface
{
    /**
     * Método para validação do usuário
     *
     * @param string $username Nome de usuário que será validado
     * @param string $password Senha do usuário que será validado
     *
     * @return array ['error' => boolean, 'data' => string|array]
     *               Em caso de error = true o campo data deve retornar a mensagem de erro no formato string
     *               Se error for igual a false, o campo data deve retornar os dados do usuário
     */
    public function validate(string $username, string $password): array;
}
