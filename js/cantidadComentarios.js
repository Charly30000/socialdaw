window.onload = function () {
    let comentarios = document.getElementsByClassName("comentarios");
    for (let comentario of comentarios) {
        let arrayComentario = comentario.id.split("_");
        obtenerCantidadComentario(arrayComentario[1]);
    }
}

function obtenerCantidadComentario(idPost) {
    let url = URL_PATH + "/obtenerCantidadComentarios/" + idPost;
    fetch(url)
    .then(function(respuesta) {
        return respuesta.text();
    })
    .then(function(datos) {
        let comentario = document.getElementById("cantidadComentarios_" + idPost);
        comentario.textContent = datos;
    })
}