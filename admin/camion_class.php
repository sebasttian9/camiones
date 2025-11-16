<?php
// camion_class.php - Clase para gestionar camiones

require_once 'db_config.php';

class Camion {
    private $conn;
    private $table = 'tbl_camiones'; // Ajusta el nombre de tu tabla
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // CREATE - Insertar nuevo camión
    public function crear($datos) {
        try {
            $query = "INSERT INTO {$this->table} 
                     (descripcion, precio, ciudad, agno, combustible, transmision, 
                      kilometraje, color, cilindrada, marca_id, modelo_id) 
                     VALUES 
                     (:descripcion, :precio, :ciudad, :agno, :combustible, :transmision, 
                      :kilometraje, :color, :cilindrada, :marca_id, :modelo_id)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            $stmt->bindParam(':precio', $datos['precio']);
            $stmt->bindParam(':ciudad', $datos['ciudad']);
            $stmt->bindParam(':agno', $datos['agno']);
            $stmt->bindParam(':combustible', $datos['combustible']);
            $stmt->bindParam(':transmision', $datos['transmision']);
            $stmt->bindParam(':kilometraje', $datos['kilometraje']);
            $stmt->bindParam(':color', $datos['color']);
            $stmt->bindParam(':cilindrada', $datos['cilindrada']);
            $stmt->bindParam(':marca_id', $datos['marca_id']);
            $stmt->bindParam(':modelo_id', $datos['modelo_id']);
            
            if($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch(PDOException $e) {
            error_log("Error al crear camión: " . $e->getMessage());
            return false;
        }
    }
    
    // READ - Obtener todos los camiones
    public function obtenerTodos($limit = null, $offset = 0) {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY id_camion DESC";
            
            if ($limit !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $this->conn->prepare($query);
            
            if ($limit !== null) {
                $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Error al obtener camiones: " . $e->getMessage());
            return [];
        }
    }
    
    // READ - Obtener un camión por ID
    public function obtenerPorId($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id_camion = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("Error al obtener camión: " . $e->getMessage());
            return false;
        }
    }
    
    // UPDATE - Actualizar camión
    public function actualizar($id, $datos) {
        try {
            $query = "UPDATE {$this->table} SET 
                     descripcion = :descripcion,
                     precio = :precio,
                     ciudad = :ciudad,
                     agno = :agno,
                     combustible = :combustible,
                     transmision = :transmision,
                     kilometraje = :kilometraje,
                     color = :color,
                     cilindrada = :cilindrada,
                     marca_id = :marca_id,
                     modelo_id = :modelo_id
                     WHERE id_camion = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            $stmt->bindParam(':precio', $datos['precio']);
            $stmt->bindParam(':ciudad', $datos['ciudad']);
            $stmt->bindParam(':agno', $datos['agno']);
            $stmt->bindParam(':combustible', $datos['combustible']);
            $stmt->bindParam(':transmision', $datos['transmision']);
            $stmt->bindParam(':kilometraje', $datos['kilometraje']);
            $stmt->bindParam(':color', $datos['color']);
            $stmt->bindParam(':cilindrada', $datos['cilindrada']);
            $stmt->bindParam(':marca_id', $datos['marca_id']);
            $stmt->bindParam(':modelo_id', $datos['modelo_id']);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error al actualizar camión: " . $e->getMessage());
            return false;
        }
    }
    
    // DELETE - Eliminar camión
    public function eliminar($id) {
        try {
            // Primero eliminar las imágenes asociadas
            $imagenObj = new Imagen();
            $imagenes = $imagenObj->obtenerPorCamion($id);
            foreach ($imagenes as $imagen) {
                $imagenObj->eliminar($imagen['id_imagen']);
            }
            
            $query = "DELETE FROM {$this->table} WHERE id_camion = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error al eliminar camión: " . $e->getMessage());
            return false;
        }
    }
    
    // Contar total de camiones
    public function contarTotal() {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->table}";
            $stmt = $this->conn->query($query);
            $result = $stmt->fetch();
            return $result['total'];
        } catch(PDOException $e) {
            error_log("Error al contar camiones: " . $e->getMessage());
            return 0;
        }
    }
    
    // Buscar camiones
    public function buscar($termino) {
        try {
            $query = "SELECT * FROM {$this->table} 
                     WHERE descripcion LIKE :termino 
                     OR ciudad LIKE :termino 
                     OR color LIKE :termino
                     ORDER BY id_camion DESC";
            
            $stmt = $this->conn->prepare($query);
            $busqueda = "%{$termino}%";
            $stmt->bindParam(':termino', $busqueda);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Error al buscar camiones: " . $e->getMessage());
            return [];
        }
    }
}
?>