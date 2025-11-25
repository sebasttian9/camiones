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
    public function obtenerTiposAutos() {
        try {
            $sql = "SELECT * FROM tbl_tipo_automovil ORDER BY nombre asc";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    public function obtenerModelos($id_marca) {
        try {
            $sql = "SELECT * FROM tbl_modelos where marca_id = $id_marca ORDER BY nombre_modelo asc";
            // echo $sql;
            // exit;
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
    public function obtenerCamionesPaginados($pagina = 1, $porPagina = 10, $tipo_auto="0", $SelectMarca="0", $Selectmodelo="0", $agno_inicio="0", $agno_fin="0", $precio="0", $transmision="0") {
        try {
            $inicio = ($pagina - 1) * $porPagina;

            $where = "";
            $llevaWhere = 0;
            $where2 = "";

            if($tipo_auto!='0'){

                $where .= " AND tipo_id =:tipo_id ";
                $llevaWhere = 1;
                
            }

            if($SelectMarca!='0'){

                $where .= " AND a.marca_id =:marca_id ";
                $llevaWhere = 1;
                
            }

            if($Selectmodelo!='0'){

                $where .= " AND a.modelo_id =:modelo_id ";
                $llevaWhere = 1;
                
            }            
            
            if($agno_inicio!='0'){

                $where .= " AND agno >= :agno_inicio ";
                $llevaWhere = 1;
                
            }
            
            if($agno_fin!='0'){

                $where .= " AND agno <= :agno_fin ";
                $llevaWhere = 1;
                
            }
            
            if($precio!='0'){

                $where .= " AND precio <= :precio ";
                $llevaWhere = 1;
                
            }
            
            if($transmision!='0'){

                $where .= " AND transmision =:transmision ";
                $llevaWhere = 1;
                
            }            

            if($llevaWhere){

                $where2 = " WHERE 1=1 ".$where;

            }

            $sql = "SELECT a.*, b.marca_nombre as marca,c.nombre_modelo as modelo, (select url from tbl_imagenes where camion_id = a.id_camion and orden = 1 limit 1) as img_camion 
                    FROM tbl_camiones a 
                    left join tbl_marcas b on a.marca_id = b.id_marca
                    left join tbl_modelos c on a.modelo_id = c.id_modelo
                    ".$where2."
                    ORDER BY id_camion ASC 
                    LIMIT :porPagina OFFSET :inicio";

            echo $sql;

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':porPagina', (int)$porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);
            if($tipo_auto!='0'){
                $stmt->bindValue(':tipo_id', (int)$tipo_auto, PDO::PARAM_INT);
            }
            if($SelectMarca!='0'){
                $stmt->bindValue(':marca_id', (int)$SelectMarca, PDO::PARAM_INT);
            }
            if($Selectmodelo!='0'){
                $stmt->bindValue(':modelo_id', (int)$Selectmodelo, PDO::PARAM_INT);
            }            
            if($agno_inicio!='0'){
                $stmt->bindValue(':agno_inicio', (int)$agno_inicio, PDO::PARAM_INT);
            } 
            if($agno_fin!='0'){
                $stmt->bindValue(':agno_fin', (int)$agno_fin, PDO::PARAM_INT);
            }
            if($precio!='0'){
                $stmt->bindValue(':precio', (int)$precio, PDO::PARAM_INT);
            }                             
            if($transmision!='0'){
                $stmt->bindValue(':transmision', $transmision, PDO::PARAM_STR);
            } 
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

    public function contarCamionesFiltro($tipo_auto = '', $marca_id = '', $modelo_id = '', $agno_inicio = '', $agno_fin = '', $precio = '', $transmision = '') {
    try {
        $sql = "SELECT COUNT(*) AS total FROM tbl_camiones WHERE 1=1";
        $params = [];
        
        // Agregar filtros dinámicamente
        if (!empty($tipo_auto)) {
            $sql .= " AND tipo_auto = :tipo_auto";
            $params[':tipo_auto'] = $tipo_auto;
        }
        
        if (!empty($marca_id)) {
            $sql .= " AND marca_id = :marca_id";
            $params[':marca_id'] = $marca_id;
        }
        
        if (!empty($modelo_id)) {
            $sql .= " AND modelo_id = :modelo_id";
            $params[':modelo_id'] = $modelo_id;
        }
        
        if (!empty($agno_inicio)) {
            $sql .= " AND anio >= :agno_inicio";
            $params[':agno_inicio'] = $agno_inicio;
        }
        
        if (!empty($agno_fin)) {
            $sql .= " AND anio <= :agno_fin";
            $params[':agno_fin'] = $agno_fin;
        }
        
        if (!empty($precio)) {
            $sql .= " AND precio <= :precio";
            $params[':precio'] = $precio;
        }
        
        if (!empty($transmision)) {
            $sql .= " AND transmision = :transmision";
            $params[':transmision'] = $transmision;
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}
}
