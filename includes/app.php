<?php 
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad(); // Cargar dotenv antes de cualquier otra cosa

require 'funciones.php';
require 'database.php'; // Ahora las variables estarán disponibles

// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setDB($db);

// Verificar que las variables están siendo cargadas
// echo 'DB_HOST: ' . $_ENV['DB_HOST'];
