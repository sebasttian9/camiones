<?php

require_once "Conexion.php";

class Camiones {

    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->conectar(); // Solo una vez
    }

    public function obtenerCamiones() {
        try {
            $sql = "SELECT a.*, b.marca_nombre as marca,c.nombre_modelo as modelo, (select url from tbl_imagenes where camion_id = a.id_camion and orden = 1 limit 1) as img_camion 
                    FROM tbl_camiones a 
                    left join tbl_marcas b on a.marca_id = b.id_marca
                    left join tbl_modelos c on a.modelo_id = c.id_modelo
                    ORDER BY id_camion ASC;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error al obtener camiones: " . $e->getMessage();
            return [];
        }
    }

    // Camion por ID
    public function obtenerCamionPorId($id) {
        try {
            $sql = "SELECT a.*, b.marca_nombre as marca,c.nombre_modelo as modelo, (select url from tbl_imagenes where camion_id = a.id_camion and orden = 1 limit 1) as img_camion 
                    FROM tbl_camiones a 
                    left join tbl_marcas b on a.marca_id = b.id_marca
                    left join tbl_modelos c on a.modelo_id = c.id_modelo
                    WHERE a.id_camion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

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
