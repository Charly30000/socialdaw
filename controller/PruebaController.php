<?php
namespace controller;
use \model\OrmSocialDaw;
use \dawfony\Ti;
use \dawfony\Klasto;
require_once "funciones.php";
use \model\Usuario;
class PruebaController extends Controller {

    public function listado() {
        if (isset($_SESSION["login"])){
            session_start();
        }
        $OrmSocialDaw = new OrmSocialDaw;
        $listadoPosts = $OrmSocialDaw->listadoPosts();
        $data = ["posts" => $listadoPosts, "title" => "Listado"];
        echo Ti::render("view/listado.phtml", $data);
    }

    public function registro() {
        $data = ["title" => "Registrarse"];
        echo Ti::render("view/registro.phtml", $data);
    }

    public function registroAceptado(){
        $login = sanitizar($_POST["login"] ?? "");
        $password = sanitizar($_POST["password"] ?? "");
        $passwordRepeat = sanitizar($_POST["passwordRepeat"] ?? "");
        $nombre = sanitizar($_POST["nombre"] ?? "");
        $email = sanitizar($_POST["email"] ?? "");
        $error = false;
        if ($login === "" || $password === "" || $passwordRepeat === "" || ($password !== $passwordRepeat) ||
        $nombre === "" || $email === ""){
            $error = true;
        }
        $usuario = new Usuario;
        $usuario->login = $login;
        $existeUsuario = (new OrmSocialDaw)->comprobarRegistro($login);
        if ($error || $existeUsuario){
            $datosUsuario = compact("login", "nombre", "email");
            $data = ["title" => "Registrarse", "error" => $error, "datosUsuario" => $datosUsuario, 
            "existeUsuario" => $existeUsuario];
            echo Ti::render("view/registro.phtml", $data);
            die();
        }
        //Por hacer cosas
        $usuario->password = $password;
        $usuario->nombre = $nombre;
        $usuario->email = $email;
        $usuario->rol_id = 0;
        (new OrmSocialDaw)->registrarNuevoUsuario($usuario);
        $data = ["title" => "UsuarioCreado!"];
        echo Ti::render("view/registroCompletado.phtml", $data);
    }

    public function login() {
        $data = ["title" => "Haciendo login..."];
        echo Ti::render("view/login.phtml", $data);
    }

    public function loginAceptado() {
        $login = sanitizar($_POST["login"] ?? "");
        $password = sanitizar($_POST["password"] ?? "");
        $error = false;
        if ($login === "" || $password === "") {
            $error = true;
            $data = ["title" => "Haciendo login...", "error" => $error, "login" => $login];
            echo Ti::render("view/login.phtml", $data);
            die();
        }
        $usuarioAComprobar = new Usuario;
        $usuarioAComprobar->login = $login;
        $usuarioAComprobar->password = $password;
        $OrmSocialDaw = new OrmSocialDaw;
        $usuario = $OrmSocialDaw->comprobarLogin($usuarioAComprobar);
        
        if ($usuario) {
            //Hacer cosas de inicio de sesion
            session_start();
            $_SESSION["login"] = $login;
            $rolUsuario = (new OrmSocialDaw)->obtenerRolUsuario($login);
            $_SESSION["rol"] = $rolUsuario->rol_id;
            $data = ["title" => "Sesion Iniciada"];
            echo Ti::render("view/sesionIniciada.phtml", $data);
        } else {
            $error = true;
            $data = ["title" => "Haciendo login...", "error" => $error, "login" => $login, "existeUsuario" => true];
            echo Ti::render("view/login.phtml", $data);
            die();
        }
    }

    public function cerrarSesion() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: listado");
    }

    public function miPerfil() {
        session_start();
        $usuario = $_SESSION["login"];
        $postsUsuario = (new OrmSocialDaw)->postsUsuario($usuario);
        $datosUsuario = (new OrmSocialDaw)->obtenerUsuario($usuario);
        $data = ["title" => "Mi perfil", "datos" => compact("postsUsuario", "datosUsuario")];
        echo Ti::render("view/perfilUsuario.phtml", $data);
    }

    public function crearPost() {
        session_start();
        $data = ["title" => "Crear un nuevo Post"];
        echo Ti::render("view/crearPost.phtml", $data);
    }

    public function crearPostAceptado() {
        session_start();
        $resumen = $_POST["resumen"];
        $texto = $_POST["texto"];
        $foto = $_FILES["foto"];
        //Por hacer cosas
    }

    public function buscarUsuario($usuario = "") {
        if (isset($_SESSION["login"])){
            session_start();
        }
        if (!$usuario) {
            $usuario = $_GET["usuarioBuscado"];
        }
        $datosUsuario = (new OrmSocialDaw)->obtenerUsuario($usuario);
        if (!$datosUsuario) {
            $data = ["title" => "Usuario desconocido", "usuario" => $usuario];
            echo Ti::render("view/usuarioDesconocido.phtml", $data);
            die();
        }
        $postsUsuario = (new OrmSocialDaw)->postsUsuario($usuario);
        $leSigue = null;
        if (isset($_SESSION["login"])){
            $leSigue = (new OrmSocialDaw)->comprobarSeguimiento($_SESSION["login"], $usuario);
        }
        if ($leSigue) {
            $data = ["leSigue" => true];
        } else {
            $data = ["leSigue" => false];
        }
        $data += ["title" => "Perfil de $usuario", "datos" => compact("postsUsuario", "datosUsuario"), 
            "usuario" => $usuario];
        echo Ti::render("view/perfilUsuario.phtml", $data);
    }

    public function seguirUsuario($usuarioASeguir) {
        session_start();
        $loginUsuario = $_SESSION["login"];
        $leSigue = (new OrmSocialDaw)->comprobarSeguimiento($loginUsuario, $usuarioASeguir);
        if (!$leSigue) {
            (new OrmSocialDaw)->seguirUsuario($loginUsuario, $usuarioASeguir);
        }
        global $URL_PATH;
        header("Location: $URL_PATH/buscarUsuario?usuarioBuscado=$usuarioASeguir");
    }
}