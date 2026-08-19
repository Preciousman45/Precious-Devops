<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    private function add(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [
            'method'  => $method,
            'path'    => $path, // kept as-is, e.g. '/books/:id' — matches() does the parsing
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = [];
            if ($this->matches($route['path'], $path, $params)) {
                ($route['handler'])($params);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => "No route for {$method} {$path}"]);
    }

    
    private function matches(string $routePattern, string $requestPath, array &$params): bool
    {
        $routeParts = explode('/', trim($routePattern, '/'));
        $pathParts  = explode('/', trim($requestPath, '/'));

        if (count($routeParts) !== count($pathParts)) {
            return false;
        }

        foreach ($routeParts as $i => $part) {
            if (str_starts_with($part, ':')) {
                $params[substr($part, 1)] = $pathParts[$i]; // ':id' -> 'id'
            } elseif ($part !== $pathParts[$i]) {
                return false;
            }
        }

        return true;
    }
}