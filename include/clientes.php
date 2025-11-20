<?php

require_once "Conexion.php";

class Clientes {

    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->conectar(); // Solo una vez
    }

    public function creacionClientes($nombre_cliente,$rut, $email, $telefono, $password_raw, $telefono2, $direccion) {
        try {


        // Encriptar la contraseña
        $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);
        
        // Preparar la consulta de inserción
        $sql = "INSERT INTO tbl_clientes (nombre_cliente, rut, email, telefono, password, estado, direccion, telefono2) 
                VALUES (:nombre_cliente, :rut, :email, :telefono, :password, 1, :direccion, :telefono2)";
        
        $stmt = $this->conn->prepare($sql);
        
        // Vincular parámetros
        $stmt->bindParam(':nombre_cliente', $nombre_cliente);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':telefono2', $telefono2);
        $stmt->bindParam(':direccion', $direccion);

        return $stmt->execute();


        } catch (PDOException $e) {
            echo "Error al crear cliente : " . $e->getMessage();
            return [];
        }
    }

    public function login($email){

        try {
            //code...
                                // Buscar usuario por email y estado activo
            $stmt = $this->conn->prepare("SELECT * FROM tbl_clientes WHERE email = ? AND estado = 1 LIMIT 1");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();

            return $usuario;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }

    }

    public function verificarToken($hashedToken){

        try {

            $stmt = $this->conn->prepare("
            SELECT id_cliente FROM tbl_clientes 
            WHERE reset_token = ? 
            AND reset_token_expiry > NOW() 
            AND estado = 1
            LIMIT 1
        ");
        $stmt->execute([$hashedToken]);
        $usuario = $stmt->fetch();

        return $usuario;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }

    }


        public function actualizarContraseña($hashedPassword,$id_cliente){

        try {

            $stmt = $this->conn->prepare("
                UPDATE tbl_clientes 
                SET password = ?, reset_token = NULL, reset_token_expiry = NULL
                WHERE id_cliente = ?
            ");
            $stmt->execute([$hashedPassword, $id_cliente]);

        // return $usuario;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }

    }


        public function guardarToken($hashedResetToken, $expiry, $id_cliente){

        try {
            //code...
                                // Buscar usuario por email y estado activo
            // $stmt = $this->conn->prepare("SELECT * FROM tbl_clientes WHERE email = ? AND estado = 1 LIMIT 1");

            $stmt = $this->conn->prepare("
                UPDATE tbl_clientes 
                SET reset_token = ?, reset_token_expiry = FROM_UNIXTIME(?)
                WHERE id_cliente = ?
            ");
            $stmt->execute([$hashedResetToken, $expiry, $id_cliente]);

            // return $usuario;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }

    }


        public function guardarTokenCookies($hashedToken, $expiry, $id_cliente){

        try {
            //code...
                                // Buscar usuario por email y estado activo
            // $stmt = $this->conn->prepare("SELECT * FROM tbl_clientes WHERE email = ? AND estado = 1 LIMIT 1");

            $stmt = $this->conn->prepare("
                     UPDATE tbl_clientes 
                     SET remember_token = ?, token_expiry = FROM_UNIXTIME(?)
                     WHERE id_cliente = ?
            ");
            $stmt->execute([$hashedToken, $expiry, $id_cliente]);

            // return $usuario;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }

    }

    // // Verificar si el RUT o email ya existen.
    public function validarCliente($rut, $email) {
        try {
        
        $stmt = $this->conn->prepare("SELECT id_cliente FROM tbl_clientes WHERE rut = :rut OR email = :email");
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

            return $stmt->rowCount();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

        // Marcas
    public function obtenerMarcas() {
        try {
            $sql = "SELECT * FROM tbl_marcas ORDER BY marca_nombre asc";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

            // Modelos
    public function obtenerModelos() {
        try {
            $sql = "SELECT * FROM tbl_modelos ORDER BY nombre_modelo asc";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

                // Imagenes
    public function obtenerImabgenesCamion($id) {
        try {
            $sql = "SELECT * FROM tbl_imagenes WHERE camion_id = ? ORDER BY orden asc";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // camiones paginados
    public function obtenerCamionesPaginados($pagina = 1, $porPagina = 10) {
        try {
            $inicio = ($pagina - 1) * $porPagina;

            $sql = "SELECT a.*, b.marca_nombre as marca,c.nombre_modelo as modelo, (select url from tbl_imagenes where camion_id = a.id_camion and orden = 1 limit 1) as img_camion 
                    FROM tbl_camiones a 
                    left join tbl_marcas b on a.marca_id = b.id_marca
                    left join tbl_modelos c on a.modelo_id = c.id_modelo
                    ORDER BY id_camion ASC 
                    LIMIT :porPagina OFFSET :inicio";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':porPagina', (int)$porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


        public function contarCamiones() {
        try {
            $sql = "SELECT COUNT(*) AS total FROM tbl_camiones";
            $stmt = $this->conn->query($sql);
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
}
