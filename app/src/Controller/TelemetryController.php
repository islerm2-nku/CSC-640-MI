<?php

namespace App\Controller;

class TelemetryController extends BaseController
{
    public function uploadTelemetry()
    {
        // Check if file was uploaded
        if (!isset($_FILES['telemetry_file'])) {
            return $this->jsonResponse(['error' => 'No file uploaded'], 400);
        }

        $file = $_FILES['telemetry_file'];
        
        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $this->jsonResponse(['error' => 'File upload failed'], 400);
        }

        // Validate file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'ibt') {
            return $this->jsonResponse(['error' => 'Invalid file type. Only .ibt files are allowed'], 400);
        }

        try {
            // Call Python script to parse telemetry
            // PHP's temporary file is already created at $file['tmp_name']
            $pythonScript = '/app/scripts/telemetry_parser.py';
            $command = "python3 {$pythonScript} " . escapeshellarg($file['tmp_name']);
            $output = shell_exec($command);

            if ($output === null) {
                return $this->jsonResponse(['error' => 'Failed to process telemetry data'], 500);
            }

            // Parse JSON response from Python script
            $telemetryData = json_decode($output, true);
            if ($telemetryData === null) {
                return $this->jsonResponse(['error' => 'Invalid telemetry data format'], 500);
            }

            return $this->jsonResponse($telemetryData);

        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}