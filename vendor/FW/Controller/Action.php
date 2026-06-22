<?php

namespace FW\Controller;

use FW\Controller\Validar;
use FW\DB\Connection;

abstract class Action implements Validar {
    protected $view;
    private $params = [];
    private $routeInfo = [];

    function __construct() {
        $this->view = new \stdClass();
        $this->loadRouteFromDatabase();
        $this->parseParams();
    }

    protected function getView() {
        return $this->view;
    }

    /**
     * Carrega a rota do banco de dados com base no slug
     */
    protected function loadRouteFromDatabase() {
        $logFile = __DIR__ . '/../../../../action_debug.log';
        $timestamp = date('Y-m-d H:i:s');
        
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $slug = trim($url, '/');
        
        file_put_contents($logFile, "[$timestamp] loadRouteFromDatabase: URL=$url, slug=$slug\n", FILE_APPEND);
        
        try {
            $conexao = new Connection();
            // Conecta ao banco e busca todas as rotas ativas
            $sql = "SELECT * FROM routes WHERE status = 1";
            $stmt = $conexao->getConn()->prepare($sql);
            $stmt->execute();

            $routes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            file_put_contents($logFile, "[$timestamp] Found " . count($routes) . " routes in DB\n", FILE_APPEND);

            foreach ($routes as $route) {
                $routeSlug = trim((string) ($route['slug'] ?? ''), '/');

                if ($routeSlug === $slug) {
                    file_put_contents($logFile, "[$timestamp] Exact match found: " . $route['nome_rota'] . "\n", FILE_APPEND);
                    $this->routeInfo = $route;
                    return;
                }

                $isDynamic = !empty($route['is_dynamic']) && (int) $route['is_dynamic'] === 1;
                $pattern = trim((string) ($route['pattern'] ?? $route['slug']), '/');

                if ($isDynamic || strpos($routeSlug, '{') !== false) {
                    $regex = $this->convertPatternToRegex($pattern);
                    if (preg_match($regex, $slug, $matches)) {
                        file_put_contents($logFile, "[$timestamp] Dynamic match found: " . $route['nome_rota'] . " (pattern=$pattern)\n", FILE_APPEND);
                        $route['params'] = $this->getNamedParams($matches);
                        file_put_contents($logFile, "[$timestamp] Extracted params: " . print_r($route['params'], true) . "\n", FILE_APPEND);
                        $this->routeInfo = $route;
                        return;
                    }
                }
            }
            
            file_put_contents($logFile, "[$timestamp] No route match found\n", FILE_APPEND);
        } catch (\Throwable $ex) {
            file_put_contents($logFile, "[$timestamp] Exception in loadRouteFromDatabase: " . $ex->getMessage() . "\n", FILE_APPEND);
            // Don't throw the exception - just log it and continue
            // This allows the application to work even if database route loading fails
        }
    }

    /**
     * Retorna as informações da rota
     */
    public function getRouteInfo() {
        return $this->routeInfo;
    }

    /**
     * Processa os parâmetros da rota
     */
    protected function parseParams() {
        $url = $_SERVER['REQUEST_URI'];
        $baseRoute = explode('?', $url)[0];
        $pathParts = explode('/', trim($baseRoute, '/'));
        
        if (!empty($this->routeInfo)) {
            if (!empty($this->routeInfo['params']) && is_array($this->routeInfo['params'])) {
                $this->params = $this->routeInfo['params'];
            } else {
                // Se encontrou rota no banco, os parâmetros são tudo após o slug base
                $slugParts = explode('/', $this->routeInfo['slug']);
                $this->params = array_slice($pathParts, count($slugParts));
            }
        } else {
            // Mantém o comportamento original para rotas não encontradas
            if (count($pathParts) >= 3) {
                array_shift($pathParts);
                array_shift($pathParts);
                array_shift($pathParts);
                $this->params = $pathParts;
            } else {
                $this->params = [];
            }
        }
    }

    protected function getParams() {
        return $this->params;
    }

    protected function convertPatternToRegex($pattern) {
        $pattern = str_replace('/', '\/', $pattern);
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^\/]+)', $pattern);
        return '/^' . $regex . '$/';
    }

    protected function getNamedParams($matches) {
        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        return $params;
    }

    protected function render($view, $layout, $include = '') {
        $this->view->page = $view;
        $this->view->include = $include;

        if (file_exists("App/View/$layout.php")) {
            require "App/View/$layout.php";
        } else {
            $this->content();
        }
    }

    protected function content() {
        $classeAtual = get_class($this);
        $classeAtual = str_replace('App\\Controller\\', '', $classeAtual);
        $classeAtual = strtolower(str_replace('Controller', '', $classeAtual));

        require_once "App/View/$classeAtual/" . $this->view->page . ".php";
    }
}