<?php
namespace Mageok\CliSignalHandler;

class CliCtrlScheduler
{
    private static $instance;
    private static $ctrlCCallbacks;

    private function __construct(){
        self::$ctrlCCallbacks = array();
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, [$this,'callLinuxRegisteredBreak']);
        } else {
            sapi_windows_set_ctrl_handler([$this,'callWinRegisteredBreak']);
        }
    }

    private function __clone() {}

    public static function registerCtrlCEvent()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        $callback = func_get_args();
        
        if (empty($callback)) {
            trigger_error('No callback passed to '.__FUNCTION__.' method', E_USER_ERROR);
            return false;
        }
        if (!is_callable($callback[0])) {
            trigger_error('Invalid callback passed to the '.__FUNCTION__.' method', E_USER_ERROR);
            return false;
        }
        self::$ctrlCCallbacks[] = $callback;

        return true;
    }

    public function callWinRegisteredBreak(int $event)
    {
        switch ($event) {
            case PHP_WINDOWS_EVENT_CTRL_C:
            case PHP_WINDOWS_EVENT_CTRL_BREAK:
                echo "You have pressed CTRL+C\n";
                    foreach (self::$ctrlCCallbacks as $arguments) {
                        $callback = array_shift($arguments);
                        call_user_func_array($callback, $arguments);
                    }
                break;
        }
        die();
    }

    public function callLinuxRegisteredBreak()
    {
        foreach (self::$ctrlCCallbacks as $arguments) {
            $callback = array_shift($arguments);
            call_user_func_array($callback, $arguments);
        }
        die();
    }
}

