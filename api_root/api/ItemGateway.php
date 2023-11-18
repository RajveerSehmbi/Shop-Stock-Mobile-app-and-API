<?php

class ItemGateway{

    private PDO $conn;
    public function __construct(Database $db){

        $this->conn = $db->getConnection();

    }

    public function getAll(): array{

        $sql = "SELECT * FROM items";
        $stmt = $this->conn->query($sql);
        
        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data): void{

        $sql = "INSERT INTO items
                VALUES (:item_code, :name, :price)";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":item_code", $data["inventory_code"], PDO::PARAM_STR);
        $stmt->bindParam(":name", $data["shop_code"], PDO::PARAM_STR);
        $stmt->bindParam(":price", $data["item_code"], PDO::PARAM_STR);

        $stmt->execute();

    }

    public function get(string $id): array{

        $sql = "SELECT *
                FROM items
                WHERE barcode = :item_code";

        $stmt = $this->conn->prepare($sql); 

        $stmt->bindParam(":item_code", $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(array $data): int{

        $sql = "UPDATE items
                SET price = price + :price
                WHERE barcode = :item_code";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":item_code", $data["item_code"], PDO::PARAM_STR);
        $stmt->bindParam(":price", $data["price"], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int{

        $sql = "DELETE FROM items WHERE barcode=:id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }
}

?>