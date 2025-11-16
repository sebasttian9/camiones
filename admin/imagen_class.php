<?php
// Imagen.php - Clase para gestionar imágenes de camiones

require_once 'db_config.php';

class Imagen {
    private $conn;
    private $table = 'tbl_imagenes'; // Ajusta el nombre de tu tabla
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // Subir imagen al servidor
    public function subirImagen($archivo, $camion_id, $orden = null) {
        try {
            // Validar que es una imagen
            $permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($archivo['type'], $permitidos)) {
                throw new Exception('Tipo de archivo no permitido. Solo imágenes.');
            }
            
            // Validar tamaño
            if ($archivo['size'] > MAX_FILE_SIZE) {
                throw new Exception('El archivo es muy grande. Máximo ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB');
            }
            
            // Verificar que no exceda el límite de imágenes
            $totalImagenes = $this->contarImagenesCamion($camion_id);
            if ($totalImagenes >= MAX_IMAGES_PER_TRUCK) {
                throw new Exception('Se alcanzó el límite máximo de ' . MAX_IMAGES_PER_TRUCK . ' imágenes por camión');
            }
            
            // Generar nombre único
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombreArchivo = 'camion_' . $camion_id . '_' . uniqid() . '_' . time() . '.' . $extension;
            $rutaDestino = UPLOAD_DIR . $nombreArchivo;
            
            // Mover archivo
            if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                throw new Exception('Error al subir el archivo');
            }
            
            // Determinar orden si no se especifica
            if ($orden === null) {
                $orden = $this->obtenerSiguienteOrden($camion_id);
            }
            
            // Guardar en base de datos
            $query = "INSERT INTO {$this->table} (camion_id, url, estado, orden) 
                     VALUES (:camion_id, :url, 1, :orden)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':camion_id', $camion_id, PDO::PARAM_INT);
            $stmt->bindParam(':url', $nombreArchivo);
            $stmt->bindParam(':orden', $orden, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'id' => $this->conn->lastInsertId(),
                    'url' => $nombreArchivo
                ];
            }
            
            // Si falla la BD, eliminar archivo
            unlink($rutaDestino);
            throw new Exception('Error al guardar en base de datos');
            
        } catch(Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    // Subir múltiples imágenes
    public function subirMultiples($archivos, $camion_id) {
        $resultados = [];
        $orden = $this->obtenerSiguienteOrden($camion_id);
        
        foreach ($archivos['tmp_name'] as $key => $tmp_name) {
            if ($archivos['error'][$key] === UPLOAD_ERR_OK) {
                $archivo = [
                    'name' => $archivos['name'][$key],
                    'type' => $archivos['type'][$key],
                    'tmp_name' => $tmp_name,
                    'error' => $archivos['error'][$key],
                    'size' => $archivos['size'][$key]
                ];
                
                $resultado = $this->subirImagen($archivo, $camion_id, $orden);
                $resultados[] = $resultado;
                
                if ($resultado['success']) {
                    $orden++;
                }
            }
        }
        
        return $resultados;
    }
    
    // Obtener imágenes de un camión
    public function obtenerPorCamion($camion_id) {
        try {
            $query = "SELECT * FROM {$this->table} 
                     WHERE camion_id = :camion_id 
                     ORDER BY orden ASC, id_imagen ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':camion_id', $camion_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Error al obtener imágenes: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener una imagen por ID
    public function obtenerPorId($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id_imagen = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("Error al obtener imagen: " . $e->getMessage());
            return false;
        }
    }
    
    // Eliminar imagen
    public function eliminar($id) {
        try {
            // Obtener datos de la imagen
            $imagen = $this->obtenerPorId($id);
            if (!$imagen) {
                return false;
            }
            
            // Eliminar archivo físico
            $rutaArchivo = UPLOAD_DIR . $imagen['url'];
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            
            // Eliminar de base de datos
            $query = "DELETE FROM {$this->table} WHERE id_imagen = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error al eliminar imagen: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar orden de imagen
    public function actualizarOrden($id, $nuevoOrden) {
        try {
            $query = "UPDATE {$this->table} SET orden = :orden WHERE id_imagen = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':orden', $nuevoOrden, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error al actualizar orden: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar estado
    public function actualizarEstado($id, $estado) {
        try {
            $query = "UPDATE {$this->table} SET estado = :estado WHERE id_imagen = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            return false;
        }
    }
    
    // Contar imágenes de un camión
    public function contarImagenesCamion($camion_id) {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE camion_id = :camion_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':camion_id', $camion_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch();
            return $result['total'];
        } catch(PDOException $e) {
            error_log("Error al contar imágenes: " . $e->getMessage());
            return 0;
        }
    }
    
    // Obtener siguiente orden disponible
    private function obtenerSiguienteOrden($camion_id) {
        try {
            $query = "SELECT MAX(orden) as max_orden FROM {$this->table} WHERE camion_id = :camion_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':camion_id', $camion_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch();
            return ($result['max_orden'] ?? 0) + 1;
        } catch(PDOException $e) {
            return 1;
        }
    }
}
?>