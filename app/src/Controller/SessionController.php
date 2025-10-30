<?php

namespace App\Controller;

use App\Model\Database;

class SessionController extends BaseController
{
    public function getAllSessions()
    {
        try {
            $pdo = Database::getPdo();
            $stmt = $pdo->query("SELECT * FROM session_info ORDER BY session_date DESC, session_time DESC");
            $sessions = $stmt->fetchAll();
            
            return $this->jsonResponse(['sessions' => $sessions]);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getSession($sessionId)
    {
        try {
            $pdo = Database::getPdo();
            
            // Get session info
            $stmt = $pdo->prepare("SELECT * FROM session_info WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            $session = $stmt->fetch();
            
            if (!$session) {
                return $this->jsonResponse(['error' => 'Session not found'], 404);
            }
            
            // Get weather info
            $stmt = $pdo->prepare("SELECT * FROM weather WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            $weather = $stmt->fetch();
            
            // Get driver info
            $stmt = $pdo->prepare("SELECT * FROM driver WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            $drivers = $stmt->fetchAll();
            
            return $this->jsonResponse([
                'session_info' => $session,
                'weather' => $weather,
                'drivers' => $drivers
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
