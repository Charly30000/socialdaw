window.addEventListener("load", comprobarTextArea);

function comprobarTextArea() {
    let formulario = document.getElementById("annadirComentario");
    formulario.onsubmit = function (evt) {
        let textarea = document.getElementById("comentario").value;
        if (textarea === "") {
            alert("Debes escribir algo para añadir un comentario");
            evt.preventDefault();
        }
    }

}