<?php

class CitasModel {
  private $db;

  public function __construct() {
    $this->db = new mysqli("localhost", "root", "", "citas");
    if ($this->db->connect_error) {
      die("Error de conexión: " . $this->db->connect_error);
    }
  }

  public function insertarCita($fecha, $hora, $valor, $atendida, $paciente_id, $medico_identificacion) {
    $query = "INSERT INTO Cita (Fecha, Hora, Valor, Atendida, Paciente_Identificacion, Medico_Identificacion) VALUES ('$fecha', '$hora', '$valor', '$atendida', '$paciente_id', '$medico_identificacion')";
    $result = $this->db->query($query);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function obtenerCitasPorMedico($Medico_Identificacion) {
    if (!is_numeric($Medico_Identificacion)) {
      return false;
    }
    $query = "SELECT * FROM Cita WHERE Medico_Identificacion = '$Medico_Identificacion'";
    $result = $this->db->query($query);
    if ($result->num_rows > 0) {
      $citas = array();
      while ($row = $result->fetch_assoc()) {
        $citas[] = $row;
      }
      return $citas;
    } else {
      return false;
    }
  }


  public function obtenerCitas() {
    $query = "SELECT * FROM Cita";
    $result = $this->db->query($query);
    if ($result->num_rows > 0) {
      $citas = array();
      while ($row = $result->fetch_assoc()) {
        $citas[] = $row;
      }
      return $citas;
    } else {
      return false;
    }
  }

  public function actualizarCita($id, $fecha, $hora, $valor, $atendida, $paciente_id, $medico_identificacion) {
    $query = "UPDATE Cita SET Fecha = '$fecha', Hora = '$hora', Valor = '$valor', Atendida = '$atendida', Paciente_Identificacion = '$paciente_id', Medico_Identificacion = '$medico_identificacion' WHERE Fecha = '$id'";
    $result = $this->db->query($query);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function eliminarCita($id_cita) {
    $query = "DELETE FROM Cita WHERE id = $id_cita";
    $result = $this->db->query($query);
    if ($result) {
      return true;
    } else {
      return false;
    }
}

  public function __destruct() {
    $this->db->close();
  }
}

// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//   $citasModel = new CitasModel();
//   $citas = $citasModel->obtenerCitas();
//   if ($citas) {
//     header('Content-Type: application/json');
//     echo json_encode($citas);
//   } else {
//     echo "No se encontraron citas.";
//   }
// }

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Debe pasarse en el link el id del medico http://localhost/citas-electiva/Model/citas_model.php?medico_identificacion=12345
  if(isset($_GET['medico_identificacion'])){
    $identificacion = $_GET['medico_identificacion'];
    $citasModel = new CitasModel();
    $citas = $citasModel->obtenerCitasPorMedico($identificacion);
    if ($citas) {
      $response = array('status' => 'success', 'data' => $citas);
    } else {
      $response = array('status' => 'error', 'message' => "No se encontraron citas para el médico con identificación $identificacion.");
    }
  }
 else {
    $citasModel = new CitasModel();
    $citas = $citasModel->obtenerCitas();
    if ($citas) {
      $response = array('status' => 'success', 'data' => $citas);
    } else {
      $response = array('status' => 'error', 'message' => 'No se encontraron citas.');
    }
  }

  header('Content-Type: application/json');
  echo json_encode($response);
}

// Verificar si se ha enviado una petición POST para insertar una nueva cita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  if (!isset($data['valor'])) {
    $data['valor'] = 0; // Asignar un valor predeterminado de 0
  }

  $citasModel = new CitasModel();

  $inserted = $citasModel->insertarCita($data['fecha'], $data['hora'], $data['valor'], $data['atendida'], $data['paciente_id'], $data['medico_identificacion']);

  if ($inserted) {
    http_response_code(201);
    echo json_encode(array('message' => 'Cita agendada correctamente.'));
  } else {
    http_response_code(400);
    echo json_encode(array('message' => 'Error al agendar la cita.'));
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  $data = json_decode(file_get_contents('php://input'), true);

  $citasModel = new CitasModel();

  $updated = $citasModel->actualizarCita($data['id'], $data['fecha'], $data['hora'], $data['valor'], $data['atendida'], $data['paciente_id'], $data['medico_identificacion']);

  if ($updated) {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Cita actualizada correctamente.'));
  } else {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Error al actualizar la cita.'));
  }
}



if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  $data = json_decode(file_get_contents('php://input'), true);
  
  $citasModel = new CitasModel();
  
  $id_cita = $data['id'];
  
  $deleted = $citasModel->eliminarCita($id_cita);
  
  if ($deleted) {
    header('Content-Type: application/json');
    echo json_encode(array('mensaje' => 'Cita eliminada correctamente.'));
  } else {
    header('Content-Type: application/json');
    echo json_encode(array('mensaje' => 'Error al eliminar la cita.'));
  }
}



?>
