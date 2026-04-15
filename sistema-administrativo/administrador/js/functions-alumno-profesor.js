var tablealumnoprofesor;

document.addEventListener('DOMContentLoaded', function() {
    tablealumnoprofesor = $('#tablealumnoprofesor').DataTable({
        "processing": true,
        "serverSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": "./models/alumno-profesor/table_alumno_profesor.php",
            "dataSrc": ""
        },
        "columns": [
            {"data": "acciones"},
            {"data": "ap_id"},
            {"data": "nombre_alumno"},
            {"data": "nombre"},
            {"data": "nombre_grado"},
            {"data": "nombre_aula"},
            {"data": "nombre_materia"},
            {"data": "nombre_periodo"},
            {"data": "estadop"}
        ],
        "dom": "lBfrtip",
        "buttons": [
            {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr": "Copiar",
                "className": "btn btn-secondary"
            },
            {
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr": "Exportar a Excel",
                "className": "btn btn-success"
            },
            {
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr": "Exportar a PDF",
                "className": "btn btn-danger",
                "exportOptions": {
                    "columns": [1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr": "Exportar a CSV",
                "className": "btn btn-info"
            }
        ],
        "responsive": true,
        "destroy": true,
        "pageLength": 10,
        "order": [[0, "asc"]]
    });

    var formAlumnoProfesor = document.querySelector('#formAlumnoProfesor');
    formAlumnoProfesor.onsubmit = function(e) {
        e.preventDefault();

        var idalumnoprofesor = document.querySelector('#idalumnoprofesor').value;
        var alumno = document.querySelector('#listAlumno').value;
        var profesor = document.querySelector('#listProfesor').value;
        var periodo = document.querySelector('#listPeriodo').value;
        var estado = document.querySelector('#listEstado').value;

        if (alumno === '' || profesor === '' || periodo === '' || estado === '') {
            swal('Atención', 'Todos los campos son necesarios', 'error');
            return false;
        }

        var request = new XMLHttpRequest();
        var url = './models/alumno-profesor/ajax-alumno-profesor.php';
        var form = new FormData(formAlumnoProfesor);
        request.open('POST', url, true);
        request.send(form);
        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var data;
                try {
                    data = JSON.parse(request.responseText);
                } catch (e) {
                    swal('Error', 'Error al procesar la respuesta del servidor', 'error');
                    return;
                }
                if (data.status) {
                    $('#modalAlumnoProfesor').modal('hide');
                    formAlumnoProfesor.reset();
                    swal('Crear Proceso Alumno', data.msg, 'success');
                    tablealumnoprofesor.ajax.reload();
                } else {
                    swal('Atención', data.msg, 'error');
                }
            }
        };
    };
});

function openModalAlumnoProfesor() {
    document.querySelector('#idalumnoprofesor').value = "";
    document.querySelector('#tituloModal').innerHTML = 'Nuevo Proceso Alumno';
    document.querySelector('#action').innerHTML = 'Guardar';
    document.querySelector('#formAlumnoProfesor').reset();
    $('#modalAlumnoProfesor').modal('show');
}

window.addEventListener('load', function() {
    showAprofesor();
    showAlumno();
    showPeriodo();
}, false);

function showAprofesor() {
    var request = new XMLHttpRequest();
    var url = './models/options/options-alumnoprofesor.php';
    request.open('GET', url, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState === 4 && request.status === 200) {
            var data;
            try {
                data = JSON.parse(request.responseText);
            } catch (e) {
                swal('Error', 'Error al procesar la respuesta del servidor', 'error');
                return;
            }
            var options = '';
            data.forEach(function(valor) {
                options += `<option value="${valor.pm_id}">Profesor: ${valor.nombre}, Grado: ${valor.nombre_grado}, Aula: ${valor.nombre_aula}, Materia: ${valor.nombre_materia}</option>`;
            });
            document.querySelector('#listProfesor').innerHTML = options;
        }
    };
}

function showAlumno() {
    var request = new XMLHttpRequest();
    var url = './models/options/options-alumno.php';
    request.open('GET', url, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState === 4 && request.status === 200) {
            var data;
            try {
                data = JSON.parse(request.responseText);
            } catch (e) {
                swal('Error', 'Error al procesar la respuesta del servidor', 'error');
                return;
            }
            var options = '';
            data.forEach(function(valor) {
                options += `<option value="${valor.alumno_id}">${valor.nombre_alumno}</option>`;
            });
            document.querySelector('#listAlumno').innerHTML = options;
        }
    };
}

function showPeriodo() {
    var request = new XMLHttpRequest();
    var url = './models/options/options-periodo.php';
    request.open('GET', url, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState === 4 && request.status === 200) {
            var data;
            try {
                data = JSON.parse(request.responseText);
            } catch (e) {
                swal('Error', 'Error al procesar la respuesta del servidor', 'error');
                return;
            }
            var options = '';
            data.forEach(function(valor) {
                options += `<option value="${valor.periodo_id}">${valor.nombre_periodo}</option>`;
            });
            document.querySelector('#listPeriodo').innerHTML = options;
        }
    };
}

function editarAlumnoProfesor(id) {
    var idalumnoprofesor = id;

    document.querySelector('#tituloModal').innerHTML = 'Actualizar Proceso Alumno';
    document.querySelector('#action').innerHTML = 'Actualizar';

    var request = new XMLHttpRequest();
    var url = './models/alumno-profesor/edit-alumno-profesor.php?id=' + idalumnoprofesor;
    request.open('GET', url, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState === 4 && request.status === 200) {
            var data;
            try {
                data = JSON.parse(request.responseText);
            } catch (e) {
                swal('Error', 'Error al procesar la respuesta del servidor', 'error');
                return;
            }
            if (data.status) {
                document.querySelector('#idalumnoprofesor').value = data.data.ap_id;
                document.querySelector('#listAlumno').value = data.data.alumno_id;
                document.querySelector('#listProfesor').value = data.data.pm_id;
                document.querySelector('#listPeriodo').value = data.data.periodo_id;
                document.querySelector('#listEstado').value = data.data.estadop;

                $('#modalAlumnoProfesor').modal('show');
            } else {
                swal('Atención', data.msg, 'error');
            }
        }
    };
}

function eliminarAlumnoProfesor(id) {
    var idalumnoprofesor = id;

    swal({
        title: "Eliminar Proceso Alumno",
        text: "Realmente desea eliminar el proceso?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(confirm) {
        if (confirm) {
            var request = new XMLHttpRequest();
            var url = './models/alumno-profesor/delete-alumno-profesor.php';
            request.open('POST', url, true);
            var strData = "id=" + idalumnoprofesor;
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState === 4 && request.status === 200) {
                    var data = JSON.parse(request.responseText);
                    if (data.status) {
                        swal('Eliminar', data.msg, 'success');
                        tablealumnoprofesor.ajax.reload();
                    } else {
                        swal('Atención', data.msg, 'error');
                    }
                }
            };
        }
    });
}
