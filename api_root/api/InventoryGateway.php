<?php

class InventoryGateway{

    private PDO $conn;
    public function __construct(Database $db){

        $this->conn = $db->getConnection();

    }

    public function getAll(): array{

        if (isset($_GET['shop'])){

            $shop = $_GET['shop'];
            $sql = "SELECT * FROM inventory WHERE shop_code = :shop";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":shop", $shop);
            $stmt->execute();
        }else{

            $sql = "SELECT * FROM inventory";
            $stmt = $this->conn->query($sql);
        }
        
        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data): void{

        $sql = "INSERT INTO inventory
                VALUES (:inventory_code, :shop_code, :item_code, :quantity)";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":inventory_code", $data["inventory_code"], PDO::PARAM_STR);
        $stmt->bindParam(":shop_code", $data["shop_code"], PDO::PARAM_STR);
        $stmt->bindParam(":item_code", $data["item_code"], PDO::PARAM_STR);
        $stmt->bindParam(":quantity", $data["quantity"], PDO::PARAM_INT);

        $stmt->execute();

    }

    public function get(string $id): array{

        $sql = "SELECT *
                FROM inventory
                WHERE inventory_code = :inventory_code";

        $stmt = $this->conn->prepare($sql); 

        $stmt->bindParam(":inventory_code", $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(array $data): int{

        $sql = "UPDATE inventory
                SET quantity = quantity + :quantity
                WHERE inventory_code = :inventory_code";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":inventory_code", $data["inventory_code"], PDO::PARAM_STR);
        $stmt->bindParam(":quantity", $data["quantity"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int{

        $sql = "DELETE FROM inventory WHERE inventory_code=:id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }
}

?>