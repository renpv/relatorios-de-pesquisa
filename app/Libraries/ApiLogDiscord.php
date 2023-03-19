<?php

namespace App\Libraries;

/**
 * Biblioteca para envio de mensagens (Logs de erro no sistema) para algum canal no discord
 *
 * Modo de usar:
 *
 * 1 - Gerar o webhook id no canal do discord
 * 2 - Definir a variável DISCORD_WEBHOOK_ID no arquivo .env
 * 3 - Chamar a função send da sequinte forma: ApiLogDiscord::send('ERRO', 'Mensagem de erro');
 *
 */

class ApiLogDiscord
{
    /**
     * Método para envio de notificação para o discord
     *
     * @param string $errorLevel Nível do erro capturado
     * @param string $message    Mensagem do erro capturado
     *
     * @return void
     *
     *
     */
    public static function send(string $errorLevel, string $message): void
    {
        $opt = [
            'baseURI' => 'https://discord.com/api/webhooks/',
            'verify'  => false,
            'timeout' => 3,
        ];
        $client    = \Config\Services::curlrequest($opt);
        $webhookId = getenv('DISCORD_WEBHOOK_ID');
        $sender    = sprintf('[%s] - Mensagem: %s', $errorLevel, $message);
        $options   = [
            'form_params' => ['content' => $sender],
        ];
        try {
            $response = $client->post($webhookId, $options);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public static function sendInfo(string $message): void
    {
        self::send('INFO', $message);
    }
}
