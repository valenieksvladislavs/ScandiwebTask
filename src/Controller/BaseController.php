<?php

namespace ScandiWebTask\Controller;

use Exception;

class BaseController
{
    function __construct(protected readonly \PDO $pdo)
    {}
    protected function render_partial(string $template, array $context = []): string
    {
        extract($context);
        ob_start();

        if(!include(__DIR__ . '/../templates/' . $template))
            exit("Template Not Found");

        return ob_get_clean();
    }

    protected function render(string $template, array $context = []): string
    {
        $headerContext = array_intersect_key($context, array_flip(['title']));
        extract($headerContext);
        ob_start();
        $content = $this->render_partial($template, $context);
        include(__DIR__ . '/../templates/base-template.php');
        return ob_get_clean();
    }

    protected function getJsonBody(): array
    {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if ($contentType !== "application/json") {
            throw new Exception('Content-Type needs to be application/json');
        }

        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        if(!is_array($decoded)){
            throw new Exception('Invalid or malformed JSON request');
        }

        return $decoded;
    }

    public function actionIndex(): string
    {
        return $this->render('index.html', ['title' => 'Welcome!']);
    }

    public function actionNotFound(): string
    {
        http_response_code(404);
        return $this->render('not-found.html', ['title' => 'Not found']);
    }
}