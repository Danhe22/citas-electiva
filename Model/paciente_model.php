<?php

class PacienteModel
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli("localhost", "root", "", "citas");
        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function insertarPaciente($identificacion, $nombre, $apellido, $activo, $regimen)
    {
        $query = "INSERT INTO Paciente (Identificacion, Nombre, Apellido, Activo, Regimen) VALUES ('$identificacion', '$nombre', '$apellido', '$activo', '$regimen')";
        $result = $this->db->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerPacienteId($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $query = "SELECT * FROM Paciente WHERE Identificacion = '$id'";
        $result = $this->db->query($query);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function obtenerPacientes()
    {
        $query = "SELECT * FROM Paciente";
        $result = $this->db->query($query);
        if ($result->num_rows > 0) {
            $pacientes = array();
            while ($row = $result->fetch_assoc()) {
                $pacientes[] = $row;
            }
            return $pacientes;
        } else {
            return false;
        }
    }

    public function actualizarPaciente($id, $identificacion, $nombre, $apellido, $activo, $regimen)
    {
        $query = "UPDATE Paciente SET Identificacion = '$identificacion', Nombre = '$nombre', Apellido = '$apellido', Activo = '$activo', Regimen = '$regimen' WHERE Identificacion = '$id'";
        $result = $this->db->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminarPaciente($identificacion)
    {
        if (empty($identificacion)) {
            return false;
        }
        $query = "DELETE FROM Paciente WHERE Identificacion = $identificacion";
        $result = $this->db->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function __destruct()
    {
        $this->db->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $pacienteModel = new PacienteModel();
      $paciente = $pacienteModel->obtenerPacienteId($id);
      if ($paciente) {
        $response = array('status' => 'success', 'data' => $paciente);
      } else {
        $response = array('status' => 'error', 'message' => "No se encontró el paciente con id $id.");
      }
    } else {
      $pacienteModel = new PacienteModel();
      $pacientes = $pacienteModel->obtenerPacientes();
      if ($pacientes) {
        $response = array('status' => 'success', 'data' => $pacientes);
      } else {
        $response = array('status' => 'error', 'message' => 'No se encontraron pacientes.');
      }
    }
  
    header('Content-Type: application/json');
    echo json_encode($response);
  }
  


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pacienteModel = new PacienteModel();
    $result = $pacienteModel->insertarPaciente($data['identificacion'], $data['nombre'], $data['apellido'], $data['activo'], $data['regimen']);
    if ($result) {
        $response = array('status' => 'success', 'message' => 'Paciente registrado correctamente.');
    } else {
        $response = array('status' => 'error', 'message' => 'Error al registrar el paciente.');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['identificacion']) && isset($data['nombre']) && isset($data['apellido']) && isset($data['activo']) && isset($data['regimen'])) {
        $pacienteModel = new PacienteModel();
        $result = $pacienteModel->actualizarPaciente($data['id'], $data['identificacion'], $data['nombre'], $data['apellido'], $data['activo'], $data['regimen']);
        if ($result) {
            $response = array('status' => 'success', 'message' => 'Paciente actualizado correctamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'Error al actualizar el paciente.');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Faltan datos obligatorios.');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    $pacienteModel = new PacienteModel();

    $id_paciente = $data['id'];

    $deleted = $pacienteModel->eliminarPaciente($id_paciente);

    if ($deleted) {
        header('Content-Type: application/json');
        echo json_encode(array('mensaje' => 'Paciente eliminado correctamente.'));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('mensaje' => 'Error al eliminar el paciente.'));
    }
}
