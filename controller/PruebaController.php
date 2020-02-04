<?php
namespace controller;
use \model\OrmSocialDaw;
use \dawfony\Ti;
use \dawfony\Klasto;
require_once "funciones.php";
use \model\Usuario;
use \model\Post;
class PruebaController extends Controller {

    public function listado($pagina = 0) {
        if (isset($_SESSION["login"])){
            session_start();
        }
        $OrmSocialDaw = new OrmSocialDaw;
        $listadoPosts = $OrmSocialDaw->listadoPosts($pagina);
        $cantidadPosts = $OrmSocialDaw->obtenerCantidadPosts();
        $rolUsuario = null;
        if (isset($_SESSION["login"])){
            $rolUsuario = $OrmSocialDaw->obtenerRolUsuario($_SESSION["login"]);
        }
        $data = ["posts" => $listadoPosts, "title" => "Listado", "cantidadPosts" => $cantidadPosts["cantidadPosts"],
            "pagina" => $pagina, "rolUsuario" => $rolUsuario];
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
        $usuario = sanitizar($_SESSION["login"]);
        $postsUsuario = (new OrmSocialDaw)->postsUsuario($usuario);
        $datosUsuario = (new OrmSocialDaw)->obtenerUsuario($usuario);
        $rolUsuario = null;
        if (isset($_SESSION["login"])) {
            $rolUsuario = (new OrmSocialDaw)->obtenerRolUsuario($usuario);
        }
        $data = ["title" => "Mi perfil", "datos" => compact("postsUsuario", "datosUsuario"), 
            "rolUsuario" => $rolUsuario];
        echo Ti::render("view/perfilUsuario.phtml", $data);
    }

    public function crearPost() {
        session_start();
        $categoriasPosts = (new OrmSocialDaw)->categoriasPosts();
        $data = ["title" => "Crear un nuevo Post", "categoriasPosts" => $categoriasPosts];
        echo Ti::render("view/crearPost.phtml", $data);
    }

    public function crearPostAceptado() {
        session_start();
        $resumen = sanitizar($_POST["resumen"] ?? "Sin titulo");
        $texto = sanitizar($_POST["texto"] ?? "Sin texto");
        $categoriaPost = sanitizar($_POST["categoriaPost"]);
        $foto = $_FILES["foto"];
        //Por hacer cosas
        if ($foto["name"] === "") {
            $foto = "avatarNull.png";
        } else {
            $foto = $foto["name"];
        }
        $post = new Post();
        $post->resumen = $resumen;
        $post->texto = $texto;
        $post->foto = $foto;
        $obtenerCategoriaPost = (new OrmSocialDaw)->obtenerCategoriaPost($categoriaPost);
        $post->categoria_post_id = $obtenerCategoriaPost["id"];
        $post->usuario_login = $_SESSION["login"];
        (new OrmSocialDaw)->crearPostAceptado($post);
        //Muevo ahora la imagen
        if ($foto !== "avatarNull.png"){
            $target_dir = "assets/img/";
            $target_file = $target_dir . basename($_FILES["foto"]["name"]);
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        }
        global $URL_PATH;
        header("Location: $URL_PATH/listado");

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
        $rolUsuario = null;
        if (isset($_SESSION["login"])) {
            $loginUsuario = $_SESSION["login"];
            $rolUsuario = (new OrmSocialDaw)->obtenerRolUsuario($loginUsuario);
        }
        $data += ["title" => "Perfil de $usuario", "datos" => compact("postsUsuario", "datosUsuario"), 
            "usuario" => $usuario, "rolUsuario" => $rolUsuario];
        echo Ti::render("view/perfilUsuario.phtml", $data);
    }

    public function seguirUsuario($usuarioASeguir) {
        session_start();
        $loginUsuario = $_SESSION["login"];
        $leSigue = (new OrmSocialDaw)->comprobarSeguimiento($loginUsuario, $usuarioASeguir);
        if (!$leSigue) {
            (new OrmSocialDaw)->seguirUsuario($loginUsuario, $usuarioASeguir);
        } else {
            (new OrmSocialDaw)->dejarSeguirUsuario($loginUsuario, $usuarioASeguir);
        }
        global $URL_PATH;
        header("Location: $URL_PATH/buscarUsuario?usuarioBuscado=$usuarioASeguir");
    }

    public function misSeguidores() {
        session_start();
        $loginUsuario = $_SESSION["login"];
        $usuariosLeSiguen = (new OrmSocialDaw)->misSeguidores($loginUsuario);
        $data = ["title" => "Usuarios que te siguen", "usuariosLeSiguen" => $usuariosLeSiguen];
        echo Ti::render("view/usuariosLeSiguen.phtml", $data);
    }

    public function verPost($idPost) {
        if (isset($_SESSION["login"])){
            session_start();
        }
        $postUsuario = (new OrmSocialDaw)->obtenerPost($idPost);
        $comentariosPost = (new OrmSocialDaw)->obtenerComentarios($idPost);
        $data = ["title" => "Post del usuario", "datos" => compact("postUsuario", "comentariosPost")];
        echo Ti::render("view/PostUsuario.phtml", $data);
    }

    function comprobarCantidadLikesPost($idPost) {
        $cantidadLikes = (new OrmSocialDaw)->obtenerCantidadLikesPost($idPost);
        $haDadoLike = "no";
        if (isset($_SESSION["login"])){
            $comprobarLike = (new OrmSocialDaw)->haDadoLike($_SESSION["login"], $idPost);
            if ($comprobarLike) {
                $haDadoLike = "si";
            }
        }

        echo $cantidadLikes["contador"] . " " . $haDadoLike;
    }

    function darLike($idPost) {
        if (isset($_SESSION["login"])){
            $loginUsuario = $_SESSION["login"];
            $haDadoLike = (new OrmSocialDaw)->haDadoLike($loginUsuario, $idPost);
            if (!$haDadoLike) {
                (new OrmSocialDaw)->darLike($loginUsuario, $idPost);
            } else {
                (new OrmSocialDaw)->quitarLike($loginUsuario, $idPost);
            }
        }
    }

    function annadirComentario($idPost) {
        if (isset($_SESSION["login"])) {
            $loginUsuario = $_SESSION["login"];
            $texto = sanitizar($_POST["comentario"] ?? "");
            $idPost = sanitizar($idPost);
            (new OrmSocialDaw)->annadirComentario($idPost, $loginUsuario, $texto);
        }
        global $URL_PATH;
        header("Location: $URL_PATH/verPost/$idPost");
    }

    function obtenerCantidadComentarios($idPost) {
        $idPost = sanitizar($idPost);
        $cantidadComentarios = (new OrmSocialDaw)->obtenerCantidadComentarios($idPost);
        echo $cantidadComentarios["contador"];
    }

    function borrarPost($idPost) {
        if (isset($_SESSION["login"])){
            global $URL_PATH;
            $idPost = sanitizar($idPost);
            $loginUsuario = $_SESSION["login"];
            $rolUsuario = (new OrmSocialDaw)->obtenerRolUsuario($loginUsuario);
            $fotoPost = (new OrmSocialDaw)->obtenerImagen($idPost);
            $fotoPost = $fotoPost["foto"];
            if ($rolUsuario->rol_id === 1) {
                (new OrmSocialDaw)->borrarPost($idPost);
            }
            if ($fotoPost !== "avatarNull.png") {
                unlink("assets/img/$fotoPost");//para borrar la foto
            }
        }
        global $URL_PATH;
        header("Location: $URL_PATH/listado");
    }

    function loUltimo() {
        if (isset($_SESSION["login"])) {
            session_start();
            $loginUsuario = sanitizar($_SESSION["login"]);
            $postsUsuariosQueSigo = (new OrmSocialDaw)->postsUsuariosQueSigo($loginUsuario);
            $rolUsuario = (new OrmSocialDaw)->obtenerRolUsuario($loginUsuario);
            $data = ["title" => "Post que sigues", "postsUsuariosQueSigo" => $postsUsuariosQueSigo, 
                "rolUsuario" => $rolUsuario];
            echo Ti::render("view/LoUltimo.phtml", $data);
        }
    }

    function borrarUsuario($loginUsuarioBorrar) {
        if (isset($_SESSION["login"])) {
            $loginUsuario = sanitizar($_SESSION["login"]);
            $rolUsuario = (new OrmSocialDaw)->obtenerRolUsuario($loginUsuario);
            if ($rolUsuario->rol_id === 1) {
                (new OrmSocialDaw)->borrarUsuario($loginUsuarioBorrar);
            }
        }
        global $URL_PATH;
        header("Location: $URL_PATH/listado");
    }
}