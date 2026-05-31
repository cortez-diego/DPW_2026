<?php

namespace App;

use FW\Init\Boostrap;
use FW\Router\RouteManager;

class Route extends Boostrap
{

    public function initRoutes()
    {
        // Inicia logging de debug
        $logFile = __DIR__ . '/../route_debug.log';
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

        file_put_contents($logFile, "[$timestamp] Total routes registered: " . count($routes) . "\n", FILE_APPEND);
        $this->setRoutes($routes);
    }
}
