// Función para obtener todos los médicos de la base de datos
function obtenerMedicos() {



  fetch('http://localhost/citas-electiva/Model/medico_model.php')
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      console.log(data.status)
      if (data.status == "error") {
        const select      = document.getElementById('medico');
        const btn_cita    = document.getElementById('btn-agregar-cita');
        select.innerHTML  = '<option value="">No hay medicos disponibles</option>';
        select.disabled   = true;
        btn_cita.style.background = "gray";
        btn_cita.style.cursor = "not-allowed";
        btn_cita.disabled = true;
      } else {
        const select = document.getElementById('medico');
        select.innerHTML = '<option value="">Seleccione un médico</option>';
        data.data.forEach(medico => {
          const option = document.createElement('option');
          option.value = medico.Identificacion;
          option.text = `${medico.Nombre} ${medico.Apellido}`;
          select.appendChild(option);
        });
      }

    })
    .catch(error => {
      console.error('Error al obtener médicos:', error);
    });
}

// Función para agregar un nuevo médico a la base de datos
function agregarMedico(event) {

  event.preventDefault();
  const identificacion = document.getElementById("identificacion").value;
  const nombre = document.getElementById("nombre").value;
  const apellido = document.getElementById("apellido").value;
  const especialidad = document.getElementById("especialidad").value;




  fetch('http://localhost/citas-electiva/Model/medico_model.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      identificacion,
      nombre,
      apellido,
      especialidad
    })
  })
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      console.log(data);
      document.getElementById("formulario-medico").reset();
        const mensaje = document.getElementById('mensaje');
              mensaje.textContent = data.message; // Agregar el mensaje devuelto por la petición
              mensaje.classList.add('mostrar');


              setTimeout(() => {
                mensaje.classList.remove('mostrar'); // Remover la clase 'mostrar' para que se oculte
              }, 3000);
    })
    .catch(error => {
      console.error('Error al agregar médico:', error);
    });
}

// Función para actualizar un médico existente en la base de datos
function actualizarMedico(identificacion, nombre, apellido, especialidad) {
  fetch('actualizar_medico.php', {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      identificacion: identificacion,
      nombre: nombre,
      apellido: apellido,
      especialidad: especialidad
    })
  })
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      console.log(data);
    })
    .catch(error => {
      console.error('Error al actualizar médico:', error);
    });
}

// Función para eliminar un médico de la base de datos
function eliminarMedico(identificacion) {
  fetch('eliminar_medico.php', {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      identificacion: identificacion
    })
  })
    .then(response => response.json())
    .then(data => {
      // Aquí puedes manipular los datos obtenidos y actualizar la vista
      console.log(data);
    })
    .catch(error => {
      console.error('Error al eliminar médico:', error);
    });
}
