<?php
class Database {
    private $host = "localhost";
    private $db_name = "sistema_eventos";
    private $username = "pepe";
    private $password = "12345";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            error_log("=== INTENTANDO CONEXIÓN BD ===");
            error_log("Host: " . $this->host);
            error_log("DB: " . $this->db_name);
            error_log("User: " . $this->username);
            
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_TIMEOUT => 5
                ]
            );
            
            error_log(" CONEXIÓN PDO EXITOSA");
            
        } catch(PDOException $e) {
            error_log("ERROR DE CONEXIÓN PDO: " . $e->getMessage());
            error_log("CÓDIGO ERROR: " . $e->getCode());
            throw new Exception("No se pudo conectar a la base de datos: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>