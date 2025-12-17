<?php
class ClienteModel {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=dpweb', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle connection error without dying
            error_log('Connection failed: ' . $e->getMessage());
            $this->pdo = null;
        }
    }

    public function buscarPorDNI($dni) {
        if (!$this->pdo) {
            return false;
        }
        $stmt = $this->pdo->prepare("SELECT nombre FROM clientes WHERE dni = :dni");
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result : false;
    }
}
?>
