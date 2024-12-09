<?php

namespace App\Routes\Base;

use App\View;

class Routes
{
    protected array $routes = [];

    private string $method;

    private string $url;

    public function __construct(){
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        $this->call();
    }

    private function call(): void
    {
        if (!empty($this->method) && array_key_exists(strtolower($this->method), $this->routes)) {
            $routesByMethod = $this->routes[strtolower($this->method)];
            if (!empty($this->url) && array_key_exists($this->url, $routesByMethod)) {
                $route = $routesByMethod[$this->url];
                if (method_exists($route['class'], $route['action'])) {
                    $class = new $route['class'];
                    $response = call_user_func_array([$class, $route['action']], []);

                    if (is_string($response) && !empty($response)) {
                        echo $response;
                        exit;
                    } else if ($response instanceof View) {
                        $file = BASE_PATH.'/resources/views/'.str_replace('.', '/',$response->getName()).'.php';
                        if (file_exists($file)) {
                            $this->includeWithVariables($file, $response->getArgs());
                            exit;
                        }
                    }
                }
            }
        }
        echo '404 - Page not found';
    }

    private function includeWithVariables($filePath, $variables = array()): void
    {
        extract($variables);
        ob_start();
        include $filePath;
        $output = ob_get_clean();

        echo $output;
    }
}
