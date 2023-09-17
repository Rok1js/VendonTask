<?php

namespace Vendon\core;

use Vendon\Exceptions\NotFoundException;

class Router
{
    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var Response
     */
    public Response $response;

    /**
     * @var Authorization
     */
    public Authorization $authorization;

    /**
     * @var array
     */
    protected array $routes = [];

    /**
     * @param Request $request
     * @param Response $response
     * @param Authorization $authorization
     */
    public function __construct(Request $request, Response $response, Authorization $authorization)
    {
        $this->request = $request;
        $this->response = $response;
        $this->authorization = $authorization;
    }


    /**
     * @param $pathname
     * @param $callback
     * @return void
     */
    // Method for storing 'GET' requests
    public function get($pathname, $callback): void
    {
        $this->routes['get'][$pathname] = $callback;
    }

    /**
     * @param $pathname
     * @param $callback
     * @return void
     */
    // Method for storing 'POST' requests
    public function post($pathname, $callback): void
    {
        $this->routes['post'][$pathname] = $callback;
    }

    /**
     * @throws NotFoundException
     */
    // Resolving requested pathname and matching it with defined routes in index.php
    public function resolve(): mixed
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            return 0;
        }

        $pathname = $this->request->getPathname();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$pathname] ?? false;

        //vvv Check if callback exists
        if ($callback === false) {
            //vvv Resolving callback in case callback has request params
            $callback = $this->getCallback();

            //vvv If callback does not exist throw an error
            if ($callback === false) {
                throw new NotFoundException();
            }
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0];
        }

        return call_user_func($callback, $this->request, $this->authorization);
    }

    /**
     * @return false|mixed
     */
    //vvv Resolving callback in case callback has request params
    public function getCallback(): mixed
    {
        $pathname = $this->request->getPathname();
        $method = $this->request->getMethod();
        $pathname = trim($pathname, '/');
        $allRoutes = $this->routes[$method];

        //vvv for all defined routes iterate over and check for passed params , e.g (id , name, etc.)
        foreach ($allRoutes as $route => $callback) {
            $route = trim($route, '/');
            $routeNames = [];

            if (!$route) {
                continue;
            }

            if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
                $routeNames = $matches[1];
            }

            $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/',
                    fn($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)',
                    $route) . "$@";

            if (preg_match_all($routeRegex, $pathname, $matchesUrls)) {
                $routeValues = [];

                for ($i = 1; $i < count($matchesUrls); $i++) {
                    $routeValues = [$matchesUrls[$i][0]];
                }

                $routeParams = array_combine($routeNames, $routeValues);
                $this->request->setRouteParams($routeParams);

                return $callback;
            }
        }

        return false;
    }
}