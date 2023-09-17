<?php

namespace Vendon\core;


use Vendon\Exceptions\UnprocessableException;

class Application
{
    /**
     * @var string
     */
    public static string $ROOT_DIRECTORY;

    /**
     * @var Application
     */
    public static Application $app;

    /**
     * @var Router
     */
    public Router $router;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var Response
     */
    public Response $response;

    /**
     * @var Database
     */
    public Database $database;

    /**
     * @var Authorization
     */
    public Authorization $authorization;

    /**
     * @param $path
     * @param $config
     */
    //vvv Constructing application
    public function __construct($path, $config)
    {
        self::$app = $this;
        self::$ROOT_DIRECTORY = $path;
        $this->request = new Request();
        $this->response = new Response();
        $this->authorization = new Authorization();
        $this->router = new Router($this->request, $this->response, $this->authorization);
        $this->database = new Database($config);
    }

    /**
     * @return void
     */
    //vvv On run resolves all the routes defined in index.php
    public function run(): void
    {
        try {
            $result = $this->router->resolve();

            if (is_array($result)) {
                echo json_encode($result);
            } else {
                echo $result;
            }
        } catch (UnprocessableException $e) {
            $this->response->setStatusCode((int)$e->getCode());
            $message = $e->getOptions();
            echo json_encode($message);

        } catch (\Exception $e) {
            $this->response->setStatusCode((int)$e->getCode());
            $message = $e->getMessage();
            if (is_array($message)) {
                echo json_encode($message);
            } else {
                echo $message;
            }
        }
    }
}