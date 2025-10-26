<?php
namespace App\Controller;

class BaseController
{
    protected function getJsonInput()
    {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?: [];
    }

    protected function validate(array $data, array $rules)
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            if ($rule === 'required' && empty($data[$field])) {
                $errors[$field] = 'Field is required';
            }
        }
        if ($errors) {
            throw new \Exception(json_encode($errors), 422);
        }
    }
}