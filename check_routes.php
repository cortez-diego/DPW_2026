<?php
require 'vendor/autoload.php';

$rm = FW\Router\RouteManager::getInstance();
$routes = $rm->getAllRoutes();

echo "Routes with 'dashboard' in slug or nome_rota:\n";
echo "========================================\n";

foreach($routes as $r) {
    if(strpos($r['slug'], 'dashboard') !== false || strpos($r['nome_rota'], 'dashboard') !== false) {
        print_r($r);
        echo "\n---\n";
    }
}
