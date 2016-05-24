
$(function () {
    $('#form1').validate({
        rules: {
            username: {required: true},
            nombre: {required: true},
            apellidos: {required: true},
            email: {required: true}
        },
        messages: {
            username: {required: "Debe introducir el nombre"},
            nombre: {required: "Debe introducir el nombre"},
            apellidos: {required: "Debe introducir los apellidos"},
            email: {required: "Debe introducir el correo electrónico"}
        }
    });
});