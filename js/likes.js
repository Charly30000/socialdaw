window.addEventListener("load", cargar);

function cargar() {
    let botonesLike = document.getElementsByClassName("likes");
    for (let boton of botonesLike) {
        boton.addEventListener("click", entrada);
        boton.click();
        boton.removeEventListener("click", entrada);
        boton.addEventListener("click", darLike);
    }
}

function entrada() {
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
        let contenido = cantidadLikesPost.split(" ");
        span.textContent = contenido[0];
        if (contenido[1] === "si") {
            boton.setAttribute("src", URL_PATH + "/js/likeRojo.jpg");
        } else {
            boton.setAttribute("src", URL_PATH + "/js/like.jpg");
        }
    })
}

function darLike() {
    let boton = this;
    let url = URL_PATH + "/darLike/" + boton.id;
    fetch(url)
    .then (function (respuesta){
        return respuesta.text();
    })
    .then(function(datos) {
        comprobarLike(boton);
    })
}