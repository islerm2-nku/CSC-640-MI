<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controller\ItemsController;

// Set JSON as default response type
header('Content-Type: application/json; charset=utf-8');

// Parse request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

// Basic routing
try {
    $response = null;
    $controller = new ItemsController();

    // Route patterns
    if ($uri[0] === 'api') {
        if ($uri[1] === 'items') {
            if ($method === 'GET') {
                if (isset($uri[2])) {
                    // GET /api/items/{id}
                    $response = $controller->show($uri[2]);
                } else {
                    // GET /api/items
                    $response = $controller->index();
                }
            } elseif ($method === 'POST') {
                // POST /api/items
                $response = $controller->create();
            } elseif ($method === 'PUT' && isset($uri[2])) {
                // PUT /api/items/{id}
                $response = $controller->update($uri[2]);
            } elseif ($method === 'DELETE' && isset($uri[2])) {
                // DELETE /api/items/{id}
                $response = $controller->delete($uri[2]);
            }
        }
    }

    if ($response === null) {
        throw new Exception('Not Found', 404);
    }

    // Send response
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['error' => $e->getMessage()]);
}