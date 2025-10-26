<?php
namespace App\Controller;

use App\Model\Database;

class ItemsController extends BaseController
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function index()
    {
        $stmt = $this->pdo->query('SELECT * FROM items ORDER BY id');
        return [
            'data' => $stmt->fetchAll(\PDO::FETCH_ASSOC)
        ];
    }

    public function show($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute([$id]);
        $item = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$item) {
            throw new \Exception('Item not found', 404);
        }
        
        return ['data' => $item];
    }

    public function create()
    {
        $data = $this->getJsonInput();
        $this->validate($data, ['name' => 'required']);

        $stmt = $this->pdo->prepare('INSERT INTO items (name, data) VALUES (?, ?)');
        $stmt->execute([$data['name'], json_encode($data['data'] ?? null)]);
        
        return [
            'data' => [
                'id' => $this->pdo->lastInsertId(),
                'name' => $data['name'],
                'data' => $data['data'] ?? null
            ],
            'message' => 'Item created'
        ];
    }

    public function update($id)
    {
        $data = $this->getJsonInput();
        $this->validate($data, ['name' => 'required']);

        $stmt = $this->pdo->prepare('UPDATE items SET name = ?, data = ? WHERE id = ?');
        $stmt->execute([$data['name'], json_encode($data['data'] ?? null), $id]);
        
        if ($stmt->rowCount() === 0) {
            throw new \Exception('Item not found', 404);
        }
        
        return [
            'data' => ['id' => $id] + $data,
            'message' => 'Item updated'
        ];
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM items WHERE id = ?');
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() === 0) {
            throw new \Exception('Item not found', 404);
        }
        
        return ['message' => 'Item deleted'];
    }
}