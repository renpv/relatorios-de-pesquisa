<?php

namespace App\Libraries;

/**
 * Biblioteca para envio de mensagens (Logs de erro no sistema) para algum canal no discord
 *
 * Modo de usar:
 * 1 - Gerar o webhook id no canal do discord
 * 2 - Definir a variável DISCORD_WEBHOOK_ID no arquivo .env
 * 3 - Chamar a função send da sequinte forma: ApiLogDiscord::send('ERRO', 'Mensagem de erro');
 *
 * @method void send(string $errorLevel, string $message)
 *
 */

class ApiLogDiscord
{
    /**
     * Used by the logThreshold Config setting to define
     * which errors to show.
     *
     * @var array<string, integer>
     */
    protected $logLevels = [
        'emergency' => 1,
        'alert'     => 2,
        'critical'  => 3,
        'error'     => 4,
        'warning'   => 5,
        'notice'    => 6,
        'info'      => 7,
        'debug'     => 8,
    ];

    /**
     * Format of the timestamp for log files.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Método para envio de notificação para o discord
     *
     * @param string $errorLevel Nível do erro capturado
     * @param string $message    Mensagem do erro capturado
     * @param array  $context    Contexto do erro
     *
     * @return void
     *
     *
     */
    public static function send(string $errorLevel, string $message, array $context = []): void
    {
        $opt = [
            'baseURI' => 'https://discord.com/api/webhooks/',
            'verify'  => false,
            'timeout' => 3,
        ];
        $client    = \Config\Services::curlrequest($opt);
        $webhookId = getenv('DISCORD_WEBHOOK_ID');
        $sender    = sprintf('[%s] - Mensagem: %s', $errorLevel, self::interpolate($message, $context));
        $options   = [
            'form_params' => ['content' => $sender],
        ];
        try {
            $response = $client->post($webhookId, $options);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Replaces any placeholders in the message with variables
     * from the context, as well as a few special items like:
     *
     * {session_vars}
     * {post_vars}
     * {get_vars}
     * {env}
     * {env:foo}
     * {file}
     * {line}
     *
     * @param string $message
     *
     * @return string
     */
    private static function interpolate($message, array $context = [])
    {
        if (!is_string($message)) {
            return print_r($message, true);
        }

        // build a replacement array with braces around the context keys
        $replace = [];

        foreach ($context as $key => $val) {
            // Verify that the 'exception' key is actually an exception
            // or error, both of which implement the 'Throwable' interface.
            if ($key === 'exception' && $val instanceof \Throwable) {
                $val = $val->getMessage() . ' ' . clean_path($val->getFile()) . ':' . $val->getLine();
            }

            // todo - sanitize input before writing to file?
            $replace['{' . $key . '}'] = $val;
        }

        // Add special placeholders
        $replace['{post_vars}'] = '$_POST: ' . print_r($_POST, true);
        $replace['{get_vars}']  = '$_GET: ' . print_r($_GET, true);
        $replace['{env}']       = ENVIRONMENT;

        // Allow us to log the file/line that we are logging from
        if (strpos($message, '{file}') !== false) {
            [$file, $line] = self::determineFile();

            $replace['{file}'] = $file;
            $replace['{line}'] = $line;
        }

        // Match up environment variables in {env:foo} tags.
        if (strpos($message, 'env:') !== false) {
            preg_match('/env:[^}]+/', $message, $matches);

            foreach ($matches as $str) {
                $key                 = str_replace('env:', '', $str);
                $replace["{{$str}}"] = $_ENV[$key] ?? 'n/a';
            }
        }

        if (isset($_SESSION)) {
            $replace['{session_vars}'] = '$_SESSION: ' . print_r($_SESSION, true);
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * Determines the file and line that the logging call
     * was made from by analyzing the backtrace.
     * Find the earliest stack frame that is part of our logging system.
     */
    private static function determineFile(): array
    {
        $logFunctions = [
            'log_message',
            'log',
            'error',
            'debug',
            'info',
            'warning',
            'critical',
            'emergency',
            'alert',
            'notice',
        ];

        // Generate Backtrace info
        $trace = \debug_backtrace(0);

        // So we search from the bottom (earliest) of the stack frames
        $stackFrames = \array_reverse($trace);

        // Find the first reference to a Logger class method
        foreach ($stackFrames as $frame) {
            if (\in_array($frame['function'], $logFunctions, true)) {
                $file = isset($frame['file']) ? clean_path($frame['file']) : 'unknown';
                $line = $frame['line'] ?? 'unknown';

                return [
                    $file,
                    $line,
                ];
            }
        }

        return [
            'unknown',
            'unknown',
        ];
    }
}
