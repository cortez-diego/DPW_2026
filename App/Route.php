<?php

namespace App;

use FW\Init\Boostrap;
use FW\Router\RouteManager;

class Route extends Boostrap
{

    public function initRoutes()
    {
        // Inicia logging de debug
        $routeLogDir = __DIR__ . '/../';
        $logFile = is_writable($routeLogDir) ? $routeLogDir . 'route_debug.log' : sys_get_temp_dir() . '/route_debug.log';
        $timestamp = date('Y-m-d H:i:s');
        $requestUri = $_SERVER['REQUEST_URI'] ?? 'N/A';
        file_put_contents($logFile, "[$timestamp] REQUEST_URI: $requestUri\n", FILE_APPEND);

        //Não excluir a Rota abaixo
        $routes['error-404'] = array(
            'route' => '/error404',
            'controller' => 'ErrorController',
            'action' => 'error404'
        );


        $routeManager = RouteManager::getInstance();
        $dbRoutes = $routeManager->getAllRoutes();
        
        file_put_contents($logFile, "[$timestamp] DB Routes count: " . count($dbRoutes) . "\n", FILE_APPEND);

        foreach ($dbRoutes as $dbRoute) {
            // Skip any route that points to CadastroController (which doesn't exist)
            if ($dbRoute['controller'] === 'CadastroController') {
                file_put_contents($logFile, "[$timestamp] Skipping invalid route: " . $dbRoute['nome_rota'] . " -> CadastroController\n", FILE_APPEND);
                continue;
            }
            $routes[$dbRoute['nome_rota']] = array(
                'route' => '/' . $dbRoute['slug'],
                'controller' => $dbRoute['controller'],
                'action' => $dbRoute['action'],
                'is_dynamic' => $dbRoute['is_dynamic'],
                'pattern' => $dbRoute['pattern'] ?? null
            );
        }

        if (!isset($routes['dashboard_animal_editar'])) {
            $routes['dashboard_animal_editar'] = array(
                'route' => '/dashboard/animal/editar/{id}',
                'controller' => 'AnimalController',
                'action' => 'editar',
                'is_dynamic' => 1,
                'pattern' => 'dashboard/animal/editar/{id}'
            );
            file_put_contents($logFile, "[$timestamp] Added fallback route: dashboard_animal_editar\n", FILE_APPEND);
        }

        if (!isset($routes['dashboard_animal_alterar'])) {
            $routes['dashboard_animal_alterar'] = array(
                'route' => '/dashboard/animal/alterar',
                'controller' => 'AnimalController',
                'action' => 'alterar',
                'is_dynamic' => 0,
                'pattern' => null
            );
        }

        if (!isset($routes['dashboard_animal_listar'])) {
            $routes['dashboard_animal_listar'] = array(
                'route' => '/dashboard/animal/listar',
                'controller' => 'AnimalController',
                'action' => 'listar',
                'is_dynamic' => 0,
                'pattern' => null
            );
        }

        if (!isset($routes['animal_perfil'])) {
            $routes['animal_perfil'] = array(
                'route' => '/animal/perfil/{id}',
                'controller' => 'AnimalController',
                'action' => 'perfil',
                'is_dynamic' => 1,
                'pattern' => 'animal/perfil/{id}'
            );
            file_put_contents($logFile, "[$timestamp] Added fallback route: animal_perfil\n", FILE_APPEND);
        }

        // Force cadastro route to use SiteController (override database if needed)
        $routes['cadastro'] = array(
            'route' => '/cadastro',
            'controller' => 'SiteController',
            'action' => 'cadastro',
            'is_dynamic' => 0,
            'pattern' => null
        );
        file_put_contents($logFile, "[$timestamp] Forced route: cadastro -> SiteController\n", FILE_APPEND);

        // Force dashboard route to use DashboardController (override database if needed)
        $routes['dashboard'] = array(
            'route' => '/dashboard',
            'controller' => 'DashboardController',
            'action' => 'index',
            'is_dynamic' => 0,
            'pattern' => null
        );
        file_put_contents($logFile, "[$timestamp] Forced route: dashboard -> DashboardController\n", FILE_APPEND);

        if (!isset($routes['adotante_cadastrar_publico'])) {
            $routes['adotante_cadastrar_publico'] = array(
                'route' => '/adotante/cadastrarPublico',
                'controller' => 'AdotanteController',
                'action' => 'cadastrarPublico',
                'is_dynamic' => 0,
                'pattern' => null
            );
            file_put_contents($logFile, "[$timestamp] Added fallback route: adotante_cadastrar_publico\n", FILE_APPEND);
        }

        if (!isset($routes['usuario_atualizar_cargo'])) {
            $routes['usuario_atualizar_cargo'] = array(
                'route' => '/usuario/atualizarCargo',
                'controller' => 'UsuarioController',
                'action' => 'atualizarCargo',
                'is_dynamic' => 0,
                'pattern' => null
            );
            file_put_contents($logFile, "[$timestamp] Added fallback route: usuario_atualizar_cargo\n", FILE_APPEND);
        }

        if (!isset($routes['usuarios'])) {
            $routes['usuarios'] = array(
                'route' => '/usuarios',
                'controller' => 'UsuarioController',
                'action' => 'index',
                'is_dynamic' => 0,
                'pattern' => null
            );
            file_put_contents($logFile, "[$timestamp] Added fallback route: usuarios\n", FILE_APPEND);
        }

        if (!isset($routes['dashboard'])) {
            $routes['dashboard'] = array(
                'route' => '/dashboard',
                'controller' => 'DashboardController',
                'action' => 'index',
                'is_dynamic' => 0,
                'pattern' => null
            );
            file_put_contents($logFile, "[$timestamp] Added fallback route: dashboard\n", FILE_APPEND);
        }

        file_put_contents($logFile, "[$timestamp] Total routes registered: " . count($routes) . "\n", FILE_APPEND);
        $this->setRoutes($routes);
    }
}
