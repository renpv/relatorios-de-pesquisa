<?php

namespace Tests\Support\Libraries;

use App\Interfaces\ApiLoginInterface;

/**
 * Biblioteca para auxiliar no processo de validação de login na API Unilab
 *
 * Modo de usar:
 *
 * 1 - Pode ser chamada com o seguinte código:
 * (new ApiLoginSig)->validate($login, $password);
 *
 */

class ApiLoginSigMock implements ApiLoginInterface
{
    /**
     * Método para validação do usuário
     *
     * @param string $username Valores válidos são [docente|discente]
     * @param string $password Valor válido [valido]
     *                         As demais combinações retornarão usuário ou senha inválidos
     *
     * @return array ['error' => boolean, 'data' => string|array]
     */
    public function validate(string $username, string $password): array
    {
        if (!in_array($username, ['docente', 'discente']) || $password != 'valido') {
            return [
                'error' => true,
                'data'  => 'Usuário ou senha inválidos',
            ];
        }

        return [
            'error' => false,
            'data'  => $this->{$username}(),
        ];
    }

    private function docente()
    {
        return json_decode('[
            {
                "id_pessoa": 12345,
                "id_usuario": 12389,
                "nome": "DOCENTE FICTICIO PARA TESTE",
                "identidade": "321654987",
                "cpf_cnpj": 321654,
                "passaporte": "",
                "email": "emaildocente@unilab.edu.br",
                "login": "docente",
                "senha": null,
                "matricula_disc": null,
                "nivel_discente": null,
                "id_status_discente": null,
                "status_discente": null,
                "id_curso": null,
                "nome_curso": null,
                "id_turno": null,
                "turno": null,
                "id_servidor": 123,
                "siape": 1234567,
                "id_status_servidor": 1,
                "status_servidor": "Ativo          ",
                "id_tipo_usuario": 1,
                "tipo_usuario": "Servidor",
                "id_categoria": 1,
                "categoria": "DOCENTE",
                "status_sistema": 1
            }
        ]');
    }

    private function discente()
    {
        return json_decode('[
            {
                "id_pessoa": 1234,
                "id_usuario": 3214,
                "nome": "DISCENTE FICTICIO PARA TESTES",
                "identidade": "321654987",
                "cpf_cnpj": 32165487,
                "passaporte": "",
                "email": "discente@aluno.unilab.edu.br",
                "login": "discente",
                "senha": null,
                "matricula_disc": 3216549871,
                "nivel_discente": "GRADUACAO",
                "id_status_discente": 1,
                "status_discente": "ATIVO",
                "id_curso": 321,
                "nome_curso": "CURSO FICTICIO",
                "id_turno": 3216549,
                "turno": "Integral",
                "id_servidor": null,
                "siape": null,
                "id_status_servidor": null,
                "status_servidor": null,
                "id_tipo_usuario": 2,
                "tipo_usuario": "Aluno",
                "id_categoria": null,
                "categoria": null,
                "status_sistema": 1
            }
        ]');
    }
}
