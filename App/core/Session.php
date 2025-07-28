<?php
namespace App\Core;
class Session
{
    private static Session|null $instance=null ;

    private function __construct()
    {
        session_start();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Session();
           
        }
        return self::$instance;
    }
   

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public  function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public  function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    public  function isset(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function unset(string $key)
    {
        $value = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);
        return $value;
    }


    
}