<?php
namespace route;

class DefaultRoute extends Route {

    private $route;

    private $defaults;

    private $regex;

    private $params = array();

    public function __construct($route, array $defaults = array(), array $regex = array()) {
        $basePath = "/ferElektrijada/";
        $this->route = $basePath . $route;
        $this->defaults = $defaults;
        $this->regex = $regex;
    }

    public function getParam($name, $def = null) {
        return isset($this->params[$name]) ? str_replace('.php', '', $this->params[$name]) : (isset($this->defaults[$name]) ? $this->defaults[$name] : $def);
//        return isset($this->params[$name]) ? $this->params[$name] : (isset($this->defaults[$name]) ? $this->defaults[$name] : $def);
    }
    /**
     * @param string $url
     *
     * @return bool
     */
    public function match($url) {
        $parts = $this->regex;

        $regex = "@^" . preg_replace_callback("/<[a-z0-9_ %!čćžšđ]+>/iu", function ($match) use ($parts) {
                    $name = substr($match[0], 1, strlen($match[0]) - 2);;
                    return "(?P" . $match[0] . (isset($parts[$name]) ? $parts[$name] : ".+?") . ")";
                }, $this->route) . "$@uD";

        return (bool) preg_match($regex, $url, $this->params);

    }
    
    /**
     * @param array $array
     *
     * @return string
     */
    public function generate(array $array = array()) {
        $params = array_merge($this->defaults, $array);

        $regexFirst = "@([^<]*<[a-z0-9_]+>.*)@iu";

        $replaceFunction = function ($match) use (&$replaceFunction, $regexFirst, $params) {
            $text = end($match);

            $text = preg_replace_callback("/<([a-z0-9_]+)>/i", function ($match) use ($params) {
                    if (!isset($params[$match[1]])) {
                        throw new \InvalidArgumentException("Missing param " . $match[1]);
                    }
                    return $params[$match[1]];
                }, $text);

                return $text;
        };

        return preg_replace_callback($regexFirst, $replaceFunction, $this->route);
    }


}