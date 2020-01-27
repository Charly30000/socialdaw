window.onload = function () {
    var puedeSeguir = true;
    var formularioPost = document.getElementById("formularioPost");
    formularioPost.onsubmit = function (evt) {
        let resumen = document.getElementById("resumen").value;
        let texto = document.getElementById("texto").value;
        let foto = document.getElementById("foto");
        if (resumen === "" || texto === "") {
            alert("Debes de añadir un Titulo y un texto!");
            puedeSeguir = false;
            evt.preventDefault();
        } else {
            puedeSeguir = true;
        }
        if (puedeSeguir) {
            var regex = /.+\.png/;
            var regex2 = /.+\.jpg/;
            if (foto.value === "") {
                let confirmacion = confirm("No has seleccionado ninguna imagen, ¿deseas continuar de todos modos?");
                if (confirmacion) {
                    console.log("seguir el proceso");
                    //evt.preventDefault();
                } else {
                    //console.log("parar el proceso");
                    evt.preventDefault();
                }
            } else if (!(regex.test(foto.value) || regex2.test(foto.value))) {
                alert("El formato seleccionado no es valido, por favor, introduce un archivo .png o .jpg");
                evt.preventDefault();
            } else {
                console.log("es valido");
                //evt.preventDefault();
            }
            //Comprobaciones para saber si las categorias son las correctas
            let categoriasPostsBD = JSON.parse(categoriasPosts);
            let arrayCategorias = [];
            for (let item of categoriasPostsBD) {
                arrayCategorias.push(item.descripcion);
            }
            let categoriasPostsHTML = document.getElementsByClassName("categoriasPosts");
            for (let item of categoriasPostsHTML) {
                if (!arrayCategorias.includes(item.value)) {
                    alert("Eres un puto hacker, devuelve las cosas a su estado original ;)");
                    evt.preventDefault();
                }
            }
        }

    }

}
