<?php
class Usermodel {
    private $conn;
    private $table = 'Usuario';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nombre, $correo, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            $query = "INSERT INTO " . $this->table . " 
                     (nombre, correo_electronico, contrasena) 
                     VALUES (:nombre, :correo, :password)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':password', $hashedPassword);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
            
        } catch (PDOException $e) {
            throw new Exception("Error al crear usuario");
        }
    }

    public function emailExists($email) {
        try {
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                     WHERE correo_electronico = :email";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['count'] > 0;
            
        } catch (PDOException $e) {
            throw new Exception("Error al verificar email");
        }
    }

    public function getByEmail($correo) {
        try {
            $query = "SELECT id_usuario, nombre, correo_electronico, contrasena 
                     FROM " . $this->table . " 
                     WHERE correo_electronico = :correo LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($correo, $password) {
        try {
            $user = $this->getByEmail($correo);
            
            if ($user && password_verify($password, $user['contrasena'])) {
                unset($user['contrasena']);
                return $user;
            }
            return false;
            
        } catch (Exception $e) {
            return false;
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT id_usuario, nombre, correo_electronico 
                     FROM " . $this->table . " 
                     WHERE id_usuario = :id LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>