<?php
namespace App\Core;

use App\Services\DatabaseProvider;
use App\Repositories\CompteurRepository;
use App\Repositories\AchatRepository;
use App\Repositories\TrancheRepository;
use App\Repositories\JournalAchatRepository;
use App\Repositories\ClientRepository;
use App\Services\AchatService;

class Router {
    private array $routes;

    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    public function dispatch(): void {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route => $config) {
            $pattern = preg_replace('/\{(\w+)\}/', '([^\/]+)', $route);

            if (preg_match("#^$pattern$#", $path, $matches) 
                && in_array($method, $config['methods'])) {
                array_shift($matches);

                // Injection des dépendances selon le contrôleur
                if ($config['controller'] === \App\Controllers\CompteurController::class) {
                    $provider = new DatabaseProvider();
                    $compteurRepo = new CompteurRepository($provider);
                    $controller = new $config['controller']($compteurRepo);
                } elseif ($config['controller'] === \App\Controllers\AchatController::class) {
                    $provider = new DatabaseProvider();
                    $achatRepo = new AchatRepository($provider);
                    $compteurRepo = new CompteurRepository($provider);
                    $trancheRepo = new TrancheRepository($provider);
                    $journalAchatRepo = new JournalAchatRepository($provider);
                    $clientRepo = new ClientRepository($provider);
                    $achatService = new AchatService($achatRepo, $compteurRepo, $trancheRepo, $journalAchatRepo, $clientRepo);
                    $controller = new $config['controller']($achatService);
                } else {
                    $controller = new $config['controller']();
                }

                $controller->{$config['method']}(...$matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}