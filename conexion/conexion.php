<?php

class conexion {
    private $server;
    private $user;
    private $pass;
    private $database;
    private $conexion;
    private $conn;
  
    function __construct() {
        $listadatos = $this->datosConexion();
        foreach($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->pass = $value['DB_PASSWORD'];
            $this->database = $value['database'];
            // $conn = new mysqli($GLOBALS['serv'], $GLOBALS['usua'], $GLOBALS['pass'], $GLOBALS['bdat']);
            $this->conn =  new mysqli($this->server,$this->user,$this->pass,$this->database);
            // new mysqli($this->server,$this->user,$this->pass,$this->database);
            if ($this->conn->connect_errno) {
                printf("Connect failedd: %s\n", $this->conn->connect_error);
                exit();
            }
        }
      }


      private function datosConexion() {
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents( $direccion. "/". "config");
        return json_decode($jsondata, true);
      }


      public function consulta_($comandoSql) {
        $resultado = $this->conn->query($comandoSql);
        // $this->conn->close();
        return $resultado;
    }

    public function insertar_($comandoSql, $id, $nombre, $apellido, $correo) {
        
        $stmt = $this->conn->prepare($comandoSql);
        $stmt->bind_param("isss", $id, $nombre, $apellido, $correo);
    
        if ($stmt->execute()) {
            return "Registro exxitoso ";
        } else {
            return "Error al insertar el registro: " . $stmt->error;
        }
    }


    public function actualizar_($comandoSql, $nombre, $apellido, $correo, $id) {
        $stmt = $this->conn->prepare($comandoSql);
        $stmt->bind_param("sssi", $nombre, $apellido, $correo, $id);
        $response = array(); 
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Registro Actualizado correctamente.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error al actualizar el registro: " . $stmt->error;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        $stmt->close();
        $this->conn->close();
    }


    public function eliminar_($comandoSql, $id) {
        $stmt = $this->conn->prepare($comandoSql);
        $stmt->bind_param("i", $id);
        
        $response = array(); 
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Registro eliminado correctamente.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error al eliminar el registro: " . $stmt->error;
        }
        
       
        header('Content-Type: application/json');
        echo json_encode($response);
        
        
        $stmt->close();
        $this->conn->close();
    }
    
    
}
?>