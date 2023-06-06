<?php
require __DIR__ . '/vendor/autoload.php';

use ScandiWebTask\FormException;
use ScandiWebTask\Router;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = '127.0.0.1';
$db   = 'scandiwebtask';
$user = 'scandiwebtask';
$pass = 'scandiwebtask';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new \PDO($dsn, $user, $pass, $options);

$router = new Router($_SERVER['REQUEST_URI']);

try {
    echo $router->process($pdo);
} catch (FormException $e) {
    http_response_code(400);
    echo json_encode(['errors' => [['key' => $e->getField(), 'message' => $e->getMessage()]]]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['errors' => [['key' => 'system', 'message' => $e->getMessage()]]]);
}
