<?php

use Dotenv\Dotenv;
use Vendon\Controllers\TestsController;
use Vendon\core\Application;

require_once __DIR__ . '/../vendor/autoload.php';

//vvv Allowed Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//vvv Loading information from .env File
$env = Dotenv::createImmutable(dirname(__DIR__));
$env->load();

//vvv Database config
$config = require_once(__DIR__ . '/../config/database_config.php');

//vvv Starting new application
$app = new Application(dirname(__DIR__), $config);

//vv Defining all the routes
$app->router->get('/', [TestsController::class, 'retrieveData']);
$app->router->post('/save-user-data', [TestsController::class, 'saveUserData']);
$app->router->get('/questions-data/{id}', [TestsController::class, 'retrieveQuestions']);
$app->router->get('/answer-data/{id}', [TestsController::class, 'retrieveAnswers']);
$app->router->post('/save-user-answer', [TestsController::class, 'saveUserAnswer']);
$app->router->post('/save-final-result', [TestsController::class, 'saveFinalResult']);

$app->run();