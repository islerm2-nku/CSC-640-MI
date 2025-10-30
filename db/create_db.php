<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Model\Database;

$pdo = Database::getPdo();

// Create session_info table
$pdo->exec("
CREATE TABLE IF NOT EXISTS session_info (
  session_id VARCHAR(36) PRIMARY KEY,
  session_type VARCHAR(100),
  track_name VARCHAR(255),
  track_id INT,
  track_config VARCHAR(255),
  session_date VARCHAR(50),
  session_time VARCHAR(50),
  track_config_sector_info TEXT
) ENGINE=InnoDB;
");

// Create weather table
$pdo->exec("
CREATE TABLE IF NOT EXISTS weather (
  session_id VARCHAR(36) PRIMARY KEY,
  track_air_temp VARCHAR(50),
  track_surface_temp VARCHAR(50),
  track_precipitation VARCHAR(50),
  track_fog_level VARCHAR(50),
  track_wind_speed VARCHAR(50),
  track_wind_direction VARCHAR(50),
  FOREIGN KEY (session_id) REFERENCES session_info(session_id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

// Create driver table with composite key
$pdo->exec("
CREATE TABLE IF NOT EXISTS driver (
  session_id VARCHAR(36) NOT NULL,
  driver_user_id INT NOT NULL,
  driver_name VARCHAR(255),
  car_number VARCHAR(10),
  car_name VARCHAR(255),
  car_class_id INT,
  driver_rating INT,
  PRIMARY KEY (session_id, driver_user_id),
  FOREIGN KEY (session_id) REFERENCES session_info(session_id) ON DELETE CASCADE
) ENGINE=InnoDB;
");
// Create attribute_values table
$pdo->exec("
CREATE TABLE IF NOT EXISTS attribute_values (
  session_id VARCHAR(36) NOT NULL,
  attribute VARCHAR(255) NOT NULL,
  value MEDIUMTEXT,
  value_len INT UNSIGNED NOT NULL,
  PRIMARY KEY (session_id, attribute),
  FOREIGN KEY (session_id) REFERENCES session_info(session_id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

echo "Migration complete: session_info, weather, and driver tables created successfully\n";