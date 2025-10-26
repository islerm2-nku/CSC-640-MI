<?php
require __DIR__ . '/../../vendor/autoload.php'; // if using composer autoload
use App\Model\Database;

$pdo = Database::getPdo();

// Create items table (works for MySQL & SQLite with simple types)
$pdo->exec("
CREATE TABLE IF NOT EXISTS items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  data TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;;
");

echo "Migration complete: items table created successfully\n";