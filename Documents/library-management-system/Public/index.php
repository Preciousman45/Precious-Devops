<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

session_start();

use App\Core\Router;
use App\Controllers\User\LoginController;
use App\Controllers\User\DashboardController;
use App\Controllers\User\UserController;
use App\Controllers\Book\BookController;
use App\Controllers\Book\BorrowingController;
use App\Controllers\Inventory\InventoryController;

header('Content-Type: application/json');

$router = new Router();


$router->post('/login', fn($p) => (new LoginController())->handleLogin());
$router->post('/logout', function ($p) {
    session_unset();
    session_destroy();
    http_response_code(200);
    echo json_encode(['message' => 'Logged out successfully.']);
});


$router->get('/dashboard', fn($p) => (new DashboardController())->show());


$router->get('/users',        fn($p) => (new UserController())->readAll());
$router->get('/users/:id',    fn($p) => (new UserController())->read($p));
$router->post('/users',       fn($p) => (new UserController())->create());
$router->put('/users/:id',    fn($p) => (new UserController())->update($p));
$router->delete('/users/:id', fn($p) => (new UserController())->delete($p));


$router->get('/books',        fn($p) => (new BookController())->readAll());
$router->get('/books/:id',    fn($p) => (new BookController())->read($p));
$router->post('/books',       fn($p) => (new BookController())->create());
$router->put('/books/:id',    fn($p) => (new BookController())->update($p));
$router->delete('/books/:id', fn($p) => (new BookController())->delete($p));


$router->get('/inventory/:bookId', fn($p) => (new InventoryController())->read($p));
$router->put('/inventory/:bookId', fn($p) => (new InventoryController())->update($p));


$router->get('/borrowings',          fn($p) => (new BorrowingController())->readAll());
$router->get('/borrowings/history',  fn($p) => (new BorrowingController())->history());
$router->get('/borrowings/returned', fn($p) => (new BorrowingController())->returned());
$router->get('/borrowings/fines',    fn($p) => (new BorrowingController())->fines());
$router->post('/borrowings',         fn($p) => (new BorrowingController())->create());
$router->put('/borrowings/:bookId',  fn($p) => (new BorrowingController())->update($p));

// --- Resolve path and dispatch ---
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($scriptDir !== '/' && str_starts_with($requestPath, $scriptDir)) {
    $requestPath = substr($requestPath, strlen($scriptDir));
}

if (isset($_GET['route'])) {
    $requestPath = $_GET['route'];
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $requestPath ?: '/');