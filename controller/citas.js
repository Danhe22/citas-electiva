// Función para obtener todas las citas de la base de datos
function obtenerCitas(event) {
  event.preventDefault();
  medico_identificacion = document.getElementById("medico_identificacion").value;

  fetch(`http://localhost/citas-electiva/Model/citas_model.php?medico_identificacion=${medico_identificacion}`)
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      // console.log(data);

      if (data && data.data && data.data.length > 0) {
        // traemos la data le paciente desde el objeto global
        const dataPaciente = window.app
        let nombre_paciente;
        // console.log(dataPaciente)

        // Obtenemos la referencia al cuerpo de la tabla
        const tbody = document.querySelector('tbody');

        // Creamos una variable para ir acumulando el HTML de las filas
        let tablaHTML = '';

        // Iteramos por los datos y creamos una fila por cada cita
        data.data.forEach(cita => {

          dataPaciente.forEach(paciente => {
            if (cita.Paciente_Identificacion == paciente.Identificacion) {
              nombre_paciente = `${paciente.Nombre} ${paciente.Apellido}`
            }
            // console.log(paciente.Nombre)
          });

          document.getElementById("form").reset();

          const atendida = cita.Atendida === 1 ? "Atendida" : "Pendiente";
          // console.log(cita.id);
          // console.log(cita.Paciente_Identificacion);
          tablaHTML += `
            <tr class="botones-fila" data-id"${ cita.id }">
              <td id="fecha">${cita.Fecha}</td>
              <td id="hora">${cita.Hora}</td>
              <td id="paciente">${cita.Paciente_Identificacion}</td>
              <td id="nombre">${nombre_paciente}</td>
              <td id="valor">${cita.Valor}</td>
              <td id="atendida">${atendida}</td>
              <td class="botones-celda">
                <input class="boton-eliminar" onclick="eliminarCita(event, ${cita.id})" value="Eliminar" type="submit">
                <input class="boton-actualizar" onclick="actualizarCitas(event, ${cita.id})" value="Actualizar" type="submit">
              </td>
            </tr>
          `;
        });
        

        // Asignamos el HTML acumulado al cuerpo de la tabla
        tbody.innerHTML = tablaHTML;

      } else {
        const mensaje = document.getElementById('mensaje');
        mensaje.textContent = "No tiene citas asignadas"; // Agregar el mensaje devuelto por la petición
        mensaje.classList.add('mostrar');

        setTimeout(() => {
          // mensaje.textContent = "No tiene citas asignadas"; // Agregar el mensaje devuelto por la petición
          mensaje.classList.remove('mostrar');
        }, 3000);
      }

    })
    .catch(error => {
      console.error('Error al obtener citas:', error);
    });
}


// Función para agregar una nueva cita a la base de datos
function agregarCita(event) {

  event.preventDefault();

  const fecha = document.getElementById("fecha").value;
  const hora = document.getElementById("hora").value;
  const paciente_id = document.getElementById("paciente").value;
  const medico_identificacion = document.getElementById("medico").value;
  const atendida = false


  fetch('http://localhost/citas-electiva/Model/citas_model.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      fecha,
      hora,
      atendida,
      paciente_id,
      medico_identificacion
    })
  })
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      console.log(data);
      document.getElementById("formulario-cita").reset();
      
      const mensaje = document.getElementById('mensaje');
      mensaje.textContent = data.message; // Agregar el mensaje devuelto por la petición
      mensaje.classList.add('mostrar');
      setTimeout(() => {
        mensaje.classList.remove('mostrar'); // Remover la clase 'mostrar' para que se oculte
      }, 3000);

    })
    .catch(error => {
      console.error('Error al agregar cita:', error);
    });
}

// Función para actualizar una cita existente en la base de datos
function actualizarCitas( event, id ) {

  event.preventDefault();

  



  // Realizar la petición de actualización
  fetch(`http://localhost/citas-electiva/Model/citas_model.php?id=${ id }`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id,
      fecha: fecha,
      hora: hora,
      paciente_id: paciente_id,
      medico_identificacion: medico_identificacion,
      atendida: atendida,
    })
  })
  .then(response => response.json())
  .then(data => {
    // Aquí puedes manipular los datos obtenidos y actualizar la vista
    console.log(data);

    const mensaje = document.getElementById('mensaje');
    mensaje.textContent = data.mensaje; // Agregar el mensaje devuelto por la petición
    mensaje.classList.add('mostrar');

    setTimeout(() => {
      mensaje.classList.remove('mostrar'); // Remover la clase 'mostrar' para que se oculte
    }, 5000);


    // Si se actualizó correctamente, actualizar la fila correspondiente en la tabla
    const filaActualizar = document.querySelector(`tr[data-id="${id}"]`);

    // Si existe la fila, actualizar el contenido de las celdas correspondientes a cada campo
    if (filaActualizar) {
      const celdaFecha = filaActualizar.querySelector('#fecha');
      celdaFecha.textContent = fecha;

      const celdaHora = filaActualizar.querySelector('#hora');
      celdaHora.textContent = hora;

      const celdaPaciente = filaActualizar.querySelector('#paciente');
      celdaPaciente.textContent = paciente_id;

      const celdaMedico = filaActualizar.querySelector('#medico');
      celdaMedico.textContent = medico_identificacion;

      // const celdaAtendida = filaActualizar.querySelector('#atendida');
      // celdaAtendida.textContent = atendida;
    }
  })
  .catch(error => {
    console.error('Error al actualizar cita:', error);
  });
}

// Función para eliminar una cita de la base de datos
function eliminarCita( event, id) {
  event.preventDefault();
  fetch(`http://localhost/citas-electiva/Model/citas_model.php?id=${ id }`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id
    })
  })
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      console.log(data);
      // Buscar la fila correspondiente al registro eliminado
      const filaEliminar = document.querySelector(`tr[data-id="${id}"]`);
      // Si existe la fila, eliminarla de la tabla
      if (filaEliminar) {
        filaEliminar.remove();
      }
      const mensaje = document.getElementById('mensaje');
      mensaje.textContent = data.mensaje; // Agregar el mensaje devuelto por la petición
      mensaje.classList.add('mostrar');

      setTimeout(() => {
        mensaje.classList.remove('mostrar'); // Remover la clase 'mostrar' para que se oculte
      }, 5000);

    })
    .catch(error => {
      console.error('Error al eliminar cita:', error);
    });
}