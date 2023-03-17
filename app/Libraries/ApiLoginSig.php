<?php 

namespace App\Libraries;
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

class ApiLoginSig implements ApiLoginInterface
{
    private array $options = [
        'baseURI' =>'https://api.unilab.edu.br/',
        'verify' => false,
        'timeout' => 3,
    ];
    private $client = null;

    public function __construct(){
        $this->client = \Config\Services::curlrequest($this->options);
    }

    /**
     * Método para validação do usuário
     * 
     * @param string $username Nome de usuário que será validado
     * @param string $password Senha do usuário que será validado
     * 
     * @return array ['error' => boolean, 'data' => string|array]
     */
    public function validate(string $username, string $password):array 
    {
        $token = $this->getToken($username, $password);
        if(!$token){
            return [
                'error' => true,
                'data' => 'Usuário ou senha inválidos'
            ];            
        }
        $bond = $this->getBond($token);
        if(!$bond){
            return [
                'error' => true,
                'data' => 'Não foi possível recuperar informações do usuário'
            ];            
        }

        return [
            'error' => false,
            'data' => $bond
        ];

    }

    private function getToken(string $username, string $password)
    {
        $options = [
            'json' => ['login' => $username,'senha' => $password]
        ];
        try {
            $response = $this->client->post('api/authenticate', $options);
        } catch (\Throwable $th) {
            return false;
        }
        if ($response->getStatusCode() == 200) {
            $auth = json_decode($response->getBody());
            return $auth->access_token ?? false;
        }
        return false;
    }

    private function getBond(string $token)
    {
        $response = $this->client->get('api/bond', [
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ]);

        if ($response->getStatusCode() == 200) {
            $bond = json_decode($response->getBody());
            return $bond ?? false;
        }
        return false;
    }
}

