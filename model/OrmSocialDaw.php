<?php
namespace model;
use \dawfony\Klasto;
use \model\Post;
class OrmSocialDaw {

    public function listadoPosts($pagina = 0){
        $bd = Klasto::getInstance();
        $params = [];
        $sql = "select post.id, fecha, resumen, texto, foto, descripcion as categoria_post_id, usuario_login";
        $sql .= " from post, categoria_post where categoria_post.id = post.categoria_post_id";
        if ($pagina) {
            $offset = 2 * ($pagina - 1);
            $sql .= " LIMIT 2 OFFSET ?";
            array_push($params, $offset);
        }
        return $bd->query($sql, $params, "model\Post");
    }
    /*
        comprueba que un usuario existe al intentar registrarse
    */
    public function comprobarRegistro($login){
        $bd = Klasto::getInstance();
        $sql = "select login from usuario where login = ?";
        return $bd->queryOne($sql, [$login], "model\Usuario");
    }
    /*
        devuelve true si el usuario existe, si no existe, devuelve false
    */
    public function registrarNuevoUsuario($usuario) {
        $bd = Klasto::getInstance();
        $sql = "insert into usuario (login, password, rol_id, nombre, email) values (?, ?, ?, ?, ?)";
        $hashearPassword = password_hash($usuario->password, PASSWORD_DEFAULT);
        $ejecutar = $bd->execute($sql, [$usuario->login, $hashearPassword, $usuario->rol_id, $usuario->nombre,
        $usuario->email]);
        if ($ejecutar === 0) {
            echo "No se ha podido hacer la operacion";
            die();
        }
    }
    /*
    Comprueba si el login del usuario y su password existen y son correctos
    */
    public function comprobarLogin($usuario){
        $bd = Klasto::getInstance();
        $sql = "select login, password, rol_id from usuario where login = ?";
        $usuarioBD = $bd->queryOne($sql, [$usuario->login], "model\Usuario");

        $passwordUsuario = $usuario->password;
        if (!$usuarioBD) {
            return false;
        }
        $passwordBD = $usuarioBD->password;
        if (password_verify($passwordUsuario, $passwordBD)){
            return true;
        } else{
            return false;
        }
    }

    public function obtenerRolUsuario($login) {
        $bd = Klasto::getInstance();
        $sql = "select rol_id from usuario where login = ?";
        return $bd->queryOne($sql, [$login], "model\Usuario");
    }

    public function postsUsuario($login) {
        $bd = Klasto::getInstance();
        $sql = "select fecha, resumen, texto, foto from post where usuario_login = ?";
        return $bd->query($sql, [$login], "model\Post");
    }

    public function obtenerUsuario ($login) {
        $bd = Klasto::getInstance();
        $sql = "select login, nombre, email from usuario where login = ?";
        return $bd->query($sql, [$login], "model\Post");
    }
}
