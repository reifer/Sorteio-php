<?php
require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

define('MP_TOKEN', $_ENV['MERCADOPAGO_ACCESS_TOKEN']);