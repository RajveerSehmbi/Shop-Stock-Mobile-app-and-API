<?php

require_once('Database.php');

class InventoryController {

    public function __construct(private InventoryGateway $gateway) {
    }

    public function processRequest(string $method, ?string $id): void {
        if ($id) {
            $this->processResourceResponse($method, $id);
        }  else {
            $this->processCollectionResponse($method);
        }
    }

    private function processResourceResponse(string $method, ?string $id): void {

    }
    private function processCollectionResponse(string $method): void {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
            
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $id = $this->gateway->create($data);

                http_response_code(201);
                echo json_encode([
                    "message"=> "Item added",
                    "id" => $id
                ]);
                break;
            
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(array $data): array{

        $errors = [];

        if (empty($data["shop_code"])) {
            $errors[] = "Shop's code is required";
        }

        if (empty($data["item_code"])) {
            $errors[] = "Item's barcode is required";
        }

        if (array_key_exists("item_code", $data)) {
            if (filter_var($data["item_code"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "Item code must be an integer";
            }
        }

        if (array_key_exists("quantity", $data)) {
            if (filter_var($data["quantity"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "Quantity must be an integer";
            }
        }

        return $errors;
    }
}
?>