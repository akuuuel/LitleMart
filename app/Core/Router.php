<?php

namespace App\Core;

class Router {
    private $routes = [];
    private $middlewares = [];

    public function add($method, $path, $handler, $middlewares = []) {
        $path = preg_replace('/\{([a-z_]+)\}/', '(?P<\1>[a-z0-9\-]+)', $path);
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => '#^' . $path . '$#i',
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function get($path, $handler, $middlewares = []) {
        $this->add('GET', $path, $handler, $middlewares);
    }

    public function post($path, $handler, $middlewares = []) {
        $this->add('POST', $path, $handler, $middlewares);
    }

    public function put($path, $handler, $middlewares = []) {
        $this->add('PUT', $path, $handler, $middlewares);
    }

    public function delete($path, $handler, $middlewares = []) {
        $this->add('DELETE', $path, $handler, $middlewares);
    }

    public function resolve(Request $request) {
        $method = $request->getMethod();
        $path = $request->getPath();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Process middlewares
                foreach ($route['middlewares'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $middleware->handle($request);
                }

                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$controllerClass, $methodName] = $handler;
                    $controller = new $controllerClass();
                    return call_user_func_array([$controller, $methodName], [$request, ...array_values($params)]);
                }

                if (is_callable($handler)) {
                    return call_user_func_array($handler, [$request, ...array_values($params)]);
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
