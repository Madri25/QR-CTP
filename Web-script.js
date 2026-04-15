/* Panel Desplegable */

// Seleccionar todos los elementos con la clase 'list__button--click'
let listElements = document.querySelectorAll('.list__button--click');

// Recorrer cada elemento de botón
listElements.forEach(listElement => {
  // Agregar un detector de eventos de clic a cada botón
  listElement.addEventListener('click', () => {

    // Alternar la clase 'arrow' en el botón para la retroalimentación visual (como una flecha hacia arriba/abajo)
    listElement.classList.toggle('arrow');

    // Obtener el siguiente elemento hermano (suponiendo que es el panel que se va a expandir/contraer)
    let menu = listElement.nextElementSibling;

    // Registrar la altura de desplazamiento del panel con fines de depuración (opcional)
    console.log(menu.scrollHeight);

    // Verificar si la altura actual del panel es 0 (contraída)
    if (menu.clientHeight === 0) {
      // Si está contraído, obtener la altura de desplazamiento del panel (altura real del contenido)
      height = menu.scrollHeight;
    } else {
      // Si ya está expandido, establecer la altura en 0 (contraer)
      height = 0;
    }

    // Establecer la altura del panel utilizando literales de plantilla para una interpolación de cadenas más limpia
    menu.style.height = `${height}px`;
  });
});
