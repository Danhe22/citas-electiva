// Función para obtener todas las citas de la base de datos
function obtenerCitas(event, id_medico) {
  event.preventDefault();


  if(id_medico){
    medico_identificacion = id_medico;
  }else{
  medico_identificacion = document.getElementById("medico_identificacion").value;

  window.medico = medico_identificacion

  }

  // console.log(medico_identificacion);

  fetch(`http://localhost/citas-electiva/Model/citas_model.php?medico_identificacion=${medico_identificacion}`)
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      // console.log(event);

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
            // console.log(paciente.Identificacion)
          });

          document.getElementById("form").reset();

          // console.log(cita.Atendida);
          let atendida = cita.Atendida == 0 ? "Atendida" : "Pendiente";
          
          // console.log(cita.id);
          // console.log(cita.Paciente_Identificacion);
          // console.log(atendida);


          tablaHTML += `
            <tr class="botones-fila" data-id="${cita.id}">
              <td id="fecha">${cita.Fecha}</td>
              <td id="hora">${cita.Hora}</td>
              <td id="paciente">${cita.Paciente_Identificacion}</td>
              <td id="nombre">${nombre_paciente}</td>
              <td id="valor">${cita.Valor}</td>
              <td id="atendida">${atendida}</td>
              <td class="botones-celda">
                <button class="boton-eliminar" onclick="eliminarCita(event, ${cita.id})" >Eliminar</button>
                <button class="boton-actualizar" onclick="actualizarCitas(event, ${cita.id}, ${cita.Atendida})" >Actualizar Estado</button>
                
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
function actualizarCitas(event, id, atendida) {


  // console.log(id, fecha, hora, paciente_id, atendida);

  event.preventDefault();

  if (atendida === 0) {
    atendida = 1
  } else if(atendida === 1) {
    atendida = 0
  }


  // Realizar la petición de actualización
  fetch(`http://localhost/citas-electiva/Model/citas_model.php`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id,
      atendida: atendida
    })
  })
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      // console.log(data);
      // Actualizar el valor de atendida en la tabla HTML
      obtenerCitas(event, window.medico); // Llamamos a la función obtenerCitas para actualizar la vista
      // const fila = document.querySelector(`tr[data-id="${id}"]`);
      // const atendidaHTML = fila.querySelector('#atendida');
      // atendidaHTML.textContent = atendida === 0 ? 'Pendiente' : 'Atendida';
      
      
      
      // const mensaje = document.getElementById("mensaje");
      // if (mensaje) {
      //   mensaje.textContent = data.message;
      //   mensaje.classList.add('mostrar');
      //   setTimeout(() => {
      //     mensaje.classList.remove('mostrar');
      //   }, 5000);
      // }

    })
    .catch(error => {
      console.error('Error al actualizar cita:', error);
    });
}

// Función para eliminar una cita de la base de datos
function eliminarCita(event, id) {
  event.preventDefault();
  fetch(`http://localhost/citas-electiva/Model/citas_model.php?id=${id}`, {
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
      console.log(data);
      obtenerCitas(event); // Llamamos a la función obtenerCitas para actualizar la vista
      const mensaje = document.getElementById('mensaje');
      mensaje.textContent = data.mensaje;
      mensaje.classList.add('mostrar');
      setTimeout(() => {
        mensaje.classList.remove('mostrar');
      }, 5000);
    })
    .catch(error => {
      console.error('Error al eliminar cita:', error);
    });
}
