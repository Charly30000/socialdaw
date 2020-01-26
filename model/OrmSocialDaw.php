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
        $sql .= " order by fecha desc";
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
        $sql = "select post.id, fecha, resumen, texto, foto, descripcion as categoria_post_id, usuario_login";
        $sql .= " from post, categoria_post where categoria_post.id = post.categoria_post_id and usuario_login = ?";
        $sql .= " order by fecha desc";
        return $bd->query($sql, [$login], "model\Post");
    }

    public function obtenerUsuario ($login) {
        $bd = Klasto::getInstance();
        $sql = "select login, nombre, email from usuario where login = ?";
        return $bd->queryOne($sql, [$login], "model\Usuario");
    }

    public function comprobarSeguimiento($loginUsuario, $loginUsuarioASeguir) {
        $bd = Klasto::getInstance();
        $sql = "select usuario_login_seguidor, usuario_login_seguido from sigue";
        $sql .= " where usuario_login_seguidor = ? and usuario_login_seguido = ?";
        return $bd->queryOne($sql, [$loginUsuario, $loginUsuarioASeguir]);
    }

    public function seguirUsuario($loginUsuario, $loginUsuarioASeguir) {
        $bd = Klasto::getInstance();
        $sql = "insert into sigue (usuario_login_seguidor, usuario_login_seguido) values (?, ?)";
        $ejecutar = $bd->execute($sql, [$loginUsuario, $loginUsuarioASeguir]);
        if ($ejecutar == 0) {
            echo "No se ha realizado la operacion";
            die();
        }
    }

    public function categoriasPosts() {
        $bd = Klasto::getInstance();
        $sql = "select descripcion from categoria_post";
        return $bd->query($sql);
    }

    public function obtenerCategoriaPost($categoria) {
        $bd = Klasto::getInstance();
        $sql = "select id from categoria_post where descripcion = ?";
        return $bd->queryOne($sql, [$categoria]);
    }

    public function crearPostAceptado($post) {
        $bd = Klasto::getInstance();
        $sql = "insert into post (fecha, resumen, texto, foto, categoria_post_id, usuario_login)";
        $sql .= " values (SYSDATE(), ?, ?, ?, ?, ?)";
        $ejecutar = $bd->execute($sql, [$post->resumen, $post->texto, $post->foto, $post->categoria_post_id, 
            $post->usuario_login]);
        if ($ejecutar == 0) {
            echo "No se ha realizado la operacion";
            die();
        }
    }
}