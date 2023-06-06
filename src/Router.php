<?php

namespace ScandiWebTask;

use ReflectionClass;
use ScandiWebTask\Controller\BaseController;
use ScandiWebTask\Controller\ProductsController;

class Router
{
    private ?string $controllerName;
    private ?string $actionName;

    public function __construct($route)
    {
        $segments = explode('/', trim($route, '/'));
        $this->controllerName = $segments[0] ?? null;

        $actionWithGetParams = $segments[1] ?? 'index';
        $actionParts = explode('?', $actionWithGetParams);
        $this->actionName = $actionParts[0];
    }

    public function process(\PDO $pdo): string
    {
        $controller = new BaseController($pdo);

        if ($this->controllerName) {
            $controllerClassName = 'ScandiWebTask\\Controller\\' . ucfirst($this->controllerName) . 'Controller';
            if (in_array($controllerClassName, $this->getControllerList())) {
                $controller = new $controllerClassName($pdo);
            } else {
                $this->actionName = 'notFound';
            }
        }

        $actionMethodName = 'action' . ucfirst($this->actionName);

        if (is_callable([$controller, $actionMethodName])) {
            return $controller->$actionMethodName();
        } else {
            return $controller->actionNotFound();
        }
    }

    private function getControllerList(): array
    {
        $controllers = [];

        $dirPath = __DIR__ . '/Controller';

        $files = array_filter(scandir($dirPath), function($file) {
            return str_ends_with($file, '.php');
        });

        foreach ($files as $file) {
            $className = 'ScandiWebTask\\Controller\\' . basename($file, '.php');

            if (!class_exists($className)) {
                continue;
            }

            $reflectionClass = new ReflectionClass($className);

            if ($reflectionClass->isSubclassOf(BaseController::class)) {
                $controllers[] = $className;
            }
        }

        return $controllers;
    }
}