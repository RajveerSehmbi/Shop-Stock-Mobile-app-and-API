<?php

class InventoryGateway{

    private PDO $conn;
    public function __construct(Database $db){

        $this->conn = $db->getConnection();

    }

    public function getAll(): array{

        $sql = "SELECT * FROM inventory";
        $stmt = $this->conn->query($sql);

        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data): string{

        $sql = "INSERT INTO inventory (shop_code, item_code, quantity)
                VALUES (:shop_code, item_code, quantity)";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":shop_code", $data["name"], PDO::PARAM_STR);
        $stmt->bindParam(":item_code", $data["item_code"], PDO::PARAM_INT);
        $stmt->bindParam(":quantity", $data["quantity"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }
}

?>