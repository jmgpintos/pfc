function confirmarBorrar(imagen, id) {
    //Hay que limpiar el link porque si no se acumulan los ids al salir del modal pulsando "No"
    var link = $('#linkBorrar').attr('href');
    var x = link.lastIndexOf('/')
    link = link.substring(0, x) + '/' + id;
    document.getElementById('confirmarBorrar').style.display = 'block';
    $('#nombre-imagen').text(imagen);
    $('#linkBorrar').attr('href', link);
}
