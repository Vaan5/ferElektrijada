<?php

namespace route;

abstract class Route {

    private static $map = array();

    /**
    * @param string $url
    *
    * @return mixed
    */
    public abstract function match($url);

    /**
    * @param array $array
    *
    * @return string
    */
    public abstract function generate(array $array = array());


    /**
    * @param string $name
    * @param Route $route
    */
    public static function register($name, Route $route) {
        self::$map[$name] = $route;
    }

    /**
    * @param string $name
    *
    * @return null|Route|Route[]
    */
    public static function get($name = null) {
        if (null === $name) { return self::$map; }
        return isset(self::$map[$name]) ? self::$map[$name] : null;
    }


    public abstract function getParam($name, $def = null);

}