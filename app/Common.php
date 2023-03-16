<?php
use App\Libraries\ApiLogDiscord;
use CodeIgniter\Test\TestLogger;
use Config\Logger;
use Config\Services;

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */

 if (! function_exists('log_message')) {
    /**
     * A convenience/compatibility method for logging events through
     * the Log system.
     *
     * Allowed log levels are:
     *  - emergency
     *  - alert
     *  - critical
     *  - error
     *  - warning
     *  - notice
     *  - info
     *  - debug
     *
     * @return bool
     */
    function log_message(string $level, string $message, array $context = [])
    {
        // When running tests, we want to always ensure that the
        // TestLogger is running, which provides utilities for
        // for asserting that logs were called in the test code.
        if (ENVIRONMENT === 'testing') {
            $logger = new TestLogger(new Logger());

            return $logger->log($level, $message, $context);
        }
        ApiLogDiscord::send($level, $message);
        return Services::logger(true)->log($level, $message, $context); // @codeCoverageIgnore
    }
}