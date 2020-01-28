window.addEventListener("load", cargar);

function cargar() {
    let botonesLike = document.getElementsByClassName("likes");
    for (let boton of botonesLike) {
        boton.addEventListener("click", darLike);
    }
}

function darLike() {
    comprobarLike(this);
}

function comprobarLike(boton) {
    let url = URL_PATH + "/comprobarCantidadLikesPost/" + boton.getAttribute("id");
    fetch(url)
    .then(function (respuesta) {
        return respuesta.text();
    })
    .then(function(cantidadLikesPost) {
        let span = document.getElementById("cantidadLikes" + boton.getAttribute("id"));
        span.textContent = cantidadLikesPost;
    })
}