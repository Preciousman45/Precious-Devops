<?php

namespace App\Controllers\Inventory;

use App\Models\Inventory;
use Exception;

class InventoryController
{
    private Inventory $inventory;

    public function __construct()
    {
        $this->inventory = new Inventory();
    }

    // GET /inventory/{bookId}  (replaces old InventoryTrackingController::show)
    public function read(array $params): void
    {
        $bookId = (int) ($params['bookId'] ?? 0);

        $record = $this->inventory->trackInventory($bookId);

        if ($record === null) {
            http_response_code(404);
            echo json_encode(['error' => 'No inventory record found for that book.']);
            return;
        }

        http_response_code(200);
        echo json_encode(['inventory' => $record]);
    }

    // PUT /inventory/{bookId}  (replaces old InventoryUpdateController::handleUpdate)
    // Body: JSON { "totalCopies": 10, "availableCopies": 7 }
    public function update(array $params): void
    {
        try {
            $bookId = (int) ($params['bookId'] ?? 0);
            $input  = json_decode(file_get_contents('php://input'), true) ?? [];

            $totalCopies     = $input['totalCopies'] ?? null;
            $availableCopies = $input['availableCopies'] ?? null;

            if (!$bookId || $totalCopies === null || $availableCopies === null) {
                throw new Exception('bookId, totalCopies, and availableCopies are all required.');
            }

            $updated = $this->inventory->updateInventory(
                $bookId, (int) $totalCopies, (int) $availableCopies
            );

            if (!$updated) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update inventory record.']);
                return;
            }

            http_response_code(200);
            echo json_encode([
                'message' => 'Inventory updated successfully.',
                'inventory' => [
                    'bookId'          => $bookId,
                    'totalCopies'     => (int) $totalCopies,
                    'availableCopies' => (int) $availableCopies,
                ],
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}