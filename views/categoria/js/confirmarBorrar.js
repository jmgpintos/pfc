function confirmarBorrar(usuario, id) {
    //Hay que limpiar el link porque si no se acumulan los ids al salir del modal pulsando "No"
    var link = $('#linkBorrar').attr('href');
    var x = link.lastIndexOf('/')
    link = link.substring(0, x) + '/' + id;
    document.getElementById('confirmarBorrar').style.display = 'block';
    $('#nombre-usuario').text(usuario);
    $('#linkBorrar').attr('href', link);
}
