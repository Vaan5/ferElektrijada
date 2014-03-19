<?php

namespace dispatcher;
use route\Route;
use app\model\NotFoundException;

class DefaultDispatcher implements Dispatcher {
    
    /**
     *
     * @var Route 
     */
    private $matched;
    
    private static $instance;
    
    private function __construct() { }
    
    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new DefaultDispatcher();
        }
        return self::$instance;
    }
    
    public function getMatched() {
        return $this->matched;
    }
    
    public function dispatch() {
        
        $request = $_SERVER["REQUEST_URI"];
        
        if (false !== ($pos = strpos($request, '?'))) {
            $request = substr($request, 0, $pos);
        }

        $this->matched = null;
        
        foreach(Route::get() as $route) {
            if (!$route->match($request)) { continue; }
            $this->matched = $route;
            break;
        }
        
        if (null === $this->matched) {
            throw new NotFoundException();
        }
        
        $controller = "\\ctl\\" . ucfirst($this->matched->getParam("controller"));
//        $controller = str_replace('.php', '', $controller);
        $action = $this->matched->getParam("action");
        
        // autoloader registriran iza onog sto je u global.php
        $func = function ($className) {
            throw new \Exception();
        };
        spl_autoload_register($func);

        $ctl = null;

        try {
            $ctl = new $controller;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }

        // vrati inicijalno ponasanje
        spl_autoload_unregister($func);
        
        // postoji li akcija ? u controleru
        if (!is_callable(array($ctl, $action))) {
            throw new NotFoundException();
        }

        //pozovi akciju
        $ctl->$action();        
    }
}