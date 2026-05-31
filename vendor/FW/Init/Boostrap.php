<?php
    
namespace FW\Init;
    
abstract class Boostrap {
    
    private $routes;
    
    function __construct() {
        $this->initRoutes();
        $this->run($this->getUrl());
    }
    
    function getRoutes() {
        return $this->routes;
    }

    function setRoutes($routes) {
        $this->routes = $routes;
    }       
    
    protected function run($url) {
        $logFile = __DIR__ . '/../../../../boostrap_debug.log';
        $timestamp = date('Y-m-d H:i:s');
        
        file_put_contents($logFile, "[$timestamp] Boostrap::run called with URL: $url\n", FILE_APPEND);
        file_put_contents($logFile, "[$timestamp] Total routes: " . count($this->getRoutes()) . "\n", FILE_APPEND);
        
        $rotaValida = false;
        
        foreach ($this->getRoutes() as $key => $route) {
            file_put_contents($logFile, "[$timestamp] Checking route: $key\n", FILE_APPEND);
            
            // Verifica se é uma rota dinâmica (com parâmetros)
            if (isset($route['is_dynamic']) && $route['is_dynamic']) {
                $matches = [];
                file_put_contents($logFile, "[$timestamp]   Dynamic route, pattern: " . ($route['pattern'] ?? 'N/A') . "\n", FILE_APPEND);
                
                if ($this->matchDynamicRoute($url, $route['pattern'], $matches)) {
                    file_put_contents($logFile, "[$timestamp]   MATCHED! Instantiating " . $route['controller'] . "\n", FILE_APPEND);
                    file_put_contents($logFile, "[$timestamp]   Matches: " . print_r($matches, true) . "\n", FILE_APPEND);
                    
                    try {
                        $class = "App\\Controller\\" . $route['controller'];
                        $controller = new $class();
                        $action = $route['action'];
                        $controller->$action($matches);
                        $rotaValida = true;
                        file_put_contents($logFile, "[$timestamp]   Action executed successfully\n", FILE_APPEND);
                        break;
                    } catch (\Throwable $ex) {
                        file_put_contents($logFile, "[$timestamp]   Exception in controller: " . $ex->getMessage() . "\n", FILE_APPEND);
                        throw $ex;
                    }
                }
            } else {
                // Rota estática normal
                file_put_contents($logFile, "[$timestamp]   Static route\n", FILE_APPEND);
                
                if ($this->matchStaticRoute($url, $route['route'])) {
                    file_put_contents($logFile, "[$timestamp]   MATCHED! Instantiating " . $route['controller'] . "\n", FILE_APPEND);
                    
                    try {
                        $class = "App\\Controller\\" . $route['controller'];
                        $controller = new $class();
                        $action = $route['action'];
                        $params = $this->getParams($url, $route['route']);
                        $controller->$action($params);
                        $rotaValida = true;
                        file_put_contents($logFile, "[$timestamp]   Action executed successfully\n", FILE_APPEND);
                        break;
                    } catch (\Throwable $ex) {
                        file_put_contents($logFile, "[$timestamp]   Exception in controller: " . $ex->getMessage() . "\n", FILE_APPEND);
                        throw $ex;
                    }
                }
            }
        }
        
        if(!$rotaValida) {
            file_put_contents($logFile, "[$timestamp] No valid route found, showing 404\n", FILE_APPEND);
            $class = "App\\Controller\\ErrorController";
            $controller = new $class();                    
            $action = "error404";
            $controller->$action();
        }
    }

    protected function getUrl() {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
            
    abstract protected function initRoutes();

    /**
     * Verifica se a URL corresponde a uma rota estática
     */
    protected function matchStaticRoute($url, $route) {
        return rtrim($url, '/') === rtrim($route, '/');
    }

    /**
     * Verifica se a URL corresponde a uma rota dinâmica e captura os parâmetros
     */
    protected function matchDynamicRoute($url, $pattern, &$matches) {
        // Converte o padrão (ex: "empresa/{id}") para regex
        $regex = $this->convertPatternToRegex($pattern);
        return preg_match($regex, trim($url, '/'), $matches);
    }

    /**
     * Converte padrão de rota dinâmica para expressão regular
     */
    protected function convertPatternToRegex($pattern) {
        $pattern = str_replace('/', '\/', $pattern);
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^\/]+)', $pattern);
        return '/^' . $regex . '$/';
    }

    /**
     * Extrai parâmetros nomeados dos matches da regex
     */
    protected function getNamedParams($matches) {
        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        return $params;
    }

    /**
     * Obtém parâmetros para rotas estáticas (mantido para compatibilidade)
     */
    protected function getParams($url, $route) {
        if (strpos($route, '{') === false) {
            return null;
        }

        $params = [];
        $urlParts = explode('/', trim($url, '/'));
        $routeParts = explode('/', trim($route, '/'));

        for ($i = 0; $i < count($routeParts); $i++) {
            if (isset($routeParts[$i]) && strpos($routeParts[$i], '{') !== false && 
                isset($urlParts[$i]) && strlen($urlParts[$i]) > 0) {
                $paramName = trim($routeParts[$i], '{}');
                $params[$paramName] = $urlParts[$i];
            }
        }

        return $params;
    }
}