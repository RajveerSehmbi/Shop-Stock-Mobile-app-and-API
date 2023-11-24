<?php

require_once('Database.php');

class ItemController {

    public function __construct(private ItemGateway $gateway) {
    }

    public function processRequest(string $method, ?string $id): void {
        if ($id) {
            $this->processResourceResponse($method, $id);
        }  else {
            $this->processCollectionResponse($method);
        }
    }

    private function processResourceResponse(string $method, string $id): void {

        $item = $this->gateway->get($id);

        if (! $item) {
            http_response_code(404);
            echo json_encode(['message'=> 'Item not found']);
            return;
        }

        switch ($method) {
            case 'GET':
                echo json_encode($item);
                break;

            case 'PATCH':
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data, false);

                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $row_count = $this->gateway->update($data);

                http_response_code(201);
                echo json_encode([
                    "message"=> "Price changed",
                    "item_code"=> $id,
                    "rows updated" => $row_count
                ]);
                break;

            case "DELETE":
                $row_count = $this->gateway->delete($id);
                echo json_encode([
                    "message"=> "Item deleted",
                    "item_code"=> $id,
                    "row_count"=> $row_count
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");

        }
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
                $this->gateway->create($data);

                http_response_code(201);
                echo json_encode([
                    "message"=> "Item created",
                    "id" => $data["item_code"],
                    "name" => $data["name"]
                ]);
                break;
            
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true): array{

        $errors = [];

        if ($is_new && empty($data["item_code"])) {
            $errors[] = "Item code is required";
        }

        if ($is_new && empty($data["name"])) {
            $errors[] = "Item's name is required";
        }

        if (empty($data["price"])) {
            $errors[] = "Item's price is required";
        }

        return $errors;
    }

}





?>