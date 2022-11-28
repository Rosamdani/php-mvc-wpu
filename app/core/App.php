<?php

class App {
    protected $controller = 'home';
    protected $mathod = 'index';
    protected $params = [];
    
    public function __construct() {
        $url = $this->parseUrl();

        // mengecek jika kontroller tidak ada
        if(file_exists('../app/controllers/' . $url[0]) . '.php') {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // mengecek method apakah ada
        if( isset($url[1]) ) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // jika url masih tersisa maka itu termasuk parameter
        if(!empty($url)) {
            $this->params = array_values($url);
        }

        // menjalankan controller, method dan param
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}