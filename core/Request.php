<?php

namespace Vendon\core;

class Request
{

    /**
     * @var array
     */
    private array $routeParams = [];

    /**
     * @return string|void
     */
    //vvv Gets header token from request
    public function getToken()
    {
        $headers = getallheaders();

        if (!array_key_exists('Authorization', $headers)) {
            echo json_encode(["error" => "Authorization header is missing"]);
            exit;
        }

        if (!str_starts_with($headers['Authorization'], 'Bearer ')) {
            echo json_encode(["error" => "Bearer keyword is missing"]);
            exit;
        }

        return trim(substr($headers['Authorization'], 6));
    }

    /**
     * @return mixed|string
     */
    //vvv Gets request Pathname
    public function getPathname(): mixed
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $pos = strpos($path, '?');

        if ($pos === false) {
            return $path;
        }

        return substr($path, 0, $pos);
    }

    /**
     * @return array
     */
    //vvv Gets request body depending on request type
    public function getBody(): array
    {
        $body = [];

        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->getMethod() === 'post') {
            $data = json_decode(file_get_contents('php://input'), true);
            foreach ($data as $key => $value) {
                if (is_integer($value)) {
                    $body[$key] = $value;
                } else {
                    $body[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }

        return $body;
    }

    /**
     * @return string
     */
    //vvv Gets request method type
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return array
     */
    //vvv getter method for routeParams
    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    /**
     * @param $routeParams
     * @return $this
     */
    //vvv setter method for routeParams
    public function setRouteParams($routeParams): static
    {
        $this->routeParams = $routeParams;

        return $this;
    }

}