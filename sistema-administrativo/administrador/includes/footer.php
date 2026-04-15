   <!-- Essential javascripts for application to work-->
   <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    
    <script src="../js/plugins/pace.min.js"></script>
    <script src="../js/fontawesome.js"></script>
    <script type="text/javascript" src="../js/plugins/sweetalert.min.js"></script>
  
    <script type="text/javascript" src="../js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../js/plugins/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript" src="../js/jszip.min.js"></script>
    <script type="text/javascript" src="../js/pdfmake.min.js"></script>
    <script type="text/javascript" src="../js/vfs_fonts.js"></script>

    
   <script>
      const scripts = {
    '/Pagina_xampp/sistema-administrativo/administrador/lista_profesor_materia.php': 'js/functions-profesor-materia.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_alumno_profesor.php': 'js/functions-alumno-profesor.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_usuarios.php': 'js/functions-usuarios.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_profesores.php': 'js/functions-profesores.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_alumnos.php': 'js/functions-alumnos.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_grados.php': 'js/functions-grados.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_aulas.php': 'js/functions-aula.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_materias.php': 'js/functions-materia.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_periodos.php': 'js/functions-periodo.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_actividad.php': 'js/functions-actividad.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_correo.php': 'js/functions_correo.js',
    '/Pagina_xampp/sistema-administrativo/administrador/lista_correo.php': 'js/function_correo.js'
      };

      const currentPath = window.location.pathname;

      if (scripts[currentPath]) {
          const scriptElement = document.createElement('script');
          scriptElement.src = scripts[currentPath];
          document.head.appendChild(scriptElement);
      }

   </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
  </body>
</html>