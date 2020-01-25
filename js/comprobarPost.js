var formularioPost = document.getElementById("formularioPost");
formularioPost.onsubmit = function (evt) {
    let resumen = document.getElementById("resumen").value;
    let texto = document.getElementById("texto").value;
    let foto = document.getElementById("foto").value;
    console.log(resumen, texto, foto);
    evt.preventDefault();
}