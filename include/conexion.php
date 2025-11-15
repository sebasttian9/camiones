<?php

class Conexion {

    private $host = "localhost";
    private $dbname = "camiones";  // Cambiar por tu BD
    private $username = "root";             // Usuario típico en local
    private $password = "";                 // Cambiar si tienes clave
    private $conn;

    public function conectar() {
        try {
            if ($this->conn == null) {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                    $this->username,
                    $this->password
                );

                // Modo errores
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }

            return $this->conn;

        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}
