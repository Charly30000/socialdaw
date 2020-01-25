<?php
require 'vendor/autoload.php';
require 'cargarconfig.php';
session_start();
use NoahBuscher\Macaw\Macaw;
use controller\PruebaController;
use dawfony\KlastoException;

//listado para pantalla principal
Macaw::get($URL_PATH . "/","controller\PruebaController@listado");
Macaw::get($URL_PATH . "/listado","controller\PruebaController@listado");
Macaw::get($URL_PATH . '/registro', "controller\PruebaController@registro");
Macaw::post($URL_PATH . '/registro', "controller\PruebaController@registroAceptado");
Macaw::get($URL_PATH . '/login', "controller\PruebaController@login");
Macaw::post($URL_PATH . '/login', "controller\PruebaController@loginAceptado");
Macaw::get($URL_PATH . '/borrar', "controller\PruebaController@borrar");
Macaw::post($URL_PATH . '/cerrarSesion', "controller\PruebaController@cerrarSesion");
Macaw::get($URL_PATH . '/miPerfil', "controller\PruebaController@miPerfil");



// Captura de URL no definidas.
Macaw::error(function() {
  echo '404 :: Not Found';
});

//terminar esto
try {
  Macaw::dispatch();
}catch(KlastoException $ex) {

}

