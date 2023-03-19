<?php

class MedicoModel
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli("localhost", "root", "", "citas");
        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function insertarMedico($identificacion, $nombre, $apellido, $especialidad)
    {
        $query = "INSERT INTO Medico (Identificacion, Nombre, Apellido, Especialidad) VALUES ('$identificacion', '$nombre', '$apellido', '$especialidad')";
        $result = $this->db->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerMedicoId($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $query = "SELECT * FROM Medico WHERE Identificacion = '$id'";
        $result = $this->db->query($query);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function obtenerMedicos()
    {
        $query = "SELECT * FROM Medico";
        $result = $this->db->query($query);
        if ($result->num_rows > 0) {
            $medicos = array();
            while ($row = $result->fetch_assoc()) {
                $medicos[] = $row;
            }
            return $medicos;
        } else {
            return false;
        }
    }

    public function actualizarMedico($identificacion, $nombre, $apellido, $especialidad)
    {
        $query = "UPDATE Medico SET Identificacion = '$identificacion', Nombre = '$nombre', Apellido = '$apellido', Especialidad = '$especialidad' WHERE Identificacion = '$identificacion'";
        $result = $this->db->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminarMedico($identificacion)
    {
        if (empty($identificacion)) {
            return false;
        }
        $query = "DELETE FROM Medico WHERE Identificacion = $identificacion";
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
      $medicoModel = new MedicoModel();
      $medico = $medicoModel->obtenerMedicoId($id);
      if ($medico) {
        $response = array('status' => 'success', 'data' => $medico);
      } else {
        $response = array('status' => 'error', 'message' => "No se encontró el médico con id $id.");
      }
    } else {
      $medicoModel = new MedicoModel();
      $medicos = $medicoModel->obtenerMedicos();
      if ($medicos) {
        $response = array('status' => 'success', 'data' => $medicos);
      } else {
        $response = array('status' => 'error', 'message' => 'No se encontraron médicos.');
      }
    }
  
    header('Content-Type: application/json');
    echo json_encode($response);
  }
  
  

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
  
    $identificacion = $data['identificacion'];
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $especialidad = $data['especialidad'];
  
    $medicoModel = new MedicoModel();
    $result = $medicoModel->insertarMedico($identificacion, $nombre, $apellido, $especialidad);
  
    if ($result) {
      $response = array('status' => 'success', 'message' => 'Médico registrado exitosamente.');
    } else {
      $response = array('status' => 'error', 'message' => 'No se pudo registrar el médico.');
    }
  
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $id = $put_vars['id'];
    $nombre = $put_vars['nombre'];
    $apellido = $put_vars['apellido'];
    $especialidad = $put_vars['especialidad'];

    $medicoModel = new MedicoModel();
    $actualizado = $medicoModel->actualizarMedico($id, $nombre, $apellido, $especialidad);

    if ($actualizado) {
        $response = array('status' => 'success', 'message' => "Médico con id $id actualizado correctamente.");
    } else {
        $response = array('status' => 'error', 'message' => "No se pudo actualizar el médico con id $id.");
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
  
    $id = $data['id'];
  
    $medicoModel = new MedicoModel();
    $result = $medicoModel->eliminarMedico($id);
  
    if ($result) {
      $response = array('status' => 'success', 'message' => 'Médico eliminado exitosamente.');
    } else {
      $response = array('status' => 'error', 'message' => 'No se pudo eliminar el médico.');
    }
  
    header('Content-Type: application/json');
    echo json_encode($response);
  }
?>