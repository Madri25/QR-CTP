document.addEventListener('DOMContentLoaded', function(){
    var formNota = document.querySelector('#formNota');

    formNota.onsubmit = function(e){
        e.preventDefault();

        var ideventregada = document.querySelector('#ideventregada').value;
        var nota = document.querySelector('#nota').value;

        if(nota.trim() == ''){
            swal('Atencion','Todos los campos son obligatorios', 'error');
            return false;
        }

        var request = new XMLHttpRequest();
        var url = './models/nota/ajax-nota.php';
        var form = new FormData(formNota);

        request.open('POST', url, true);
        request.send(form);

        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                var data = JSON.parse(request.responseText);

                if(data.status) {
                    swal({
                        title: "Crear Nota",
                        type: "success",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: true
                    },function(confirm){
                        if(confirm) {        
                            $('#modalNota').modal('hide');
                            location.reload();
                            formNota.reset();
                        }
                    })
                } else {
                    swal('Atencion', data.msg, 'error');
                }
            }
        }
    }
});

function modalNota(){
    $('#modalNota').modal('show');
}