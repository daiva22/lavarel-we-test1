<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Opis\JsonSchema\Validator;
use Opis\JsonSchema\Errors\ErrorFormatter;

class JsonSchemaController extends Controller
{
    public function validateCreation()
    {
        $schemaPath = storage_path('app/schemas/service.schema.json');
        $jsonPath = storage_path('app/json/service.json');

        if (!File::exists($schemaPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Schema file not found',
                'path' => $schemaPath,
            ], 404);
        }

        if (!File::exists($jsonPath)) {
            return response()->json([
                'success' => false,
                'message' => 'JSON file not found',
                'path' => $jsonPath,
            ], 404);
        }

        $schemaContent = File::get($schemaPath);
        $jsonContent = File::get($jsonPath);

        $schema = json_decode($schemaContent);
        $data = json_decode($jsonContent);

        if (json_last_error() !== JSON_ERROR_NONE && $data === null) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid JSON in service.json',
                'error' => json_last_error_msg(),
                'raw' => $jsonContent,
            ], 422);
        }

        $schemaCheck = json_decode($schemaContent);
        if ($schemaCheck === null && trim($schemaContent) !== 'null') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid JSON in service.schema.json',
                'error' => json_last_error_msg(),
                'raw' => $schemaContent,
            ], 422);
        }

        $validator = new Validator();
        $result = $validator->validate($data, $schemaCheck);

        if ($result->isValid()) {
            return response()->json([
                'success' => true,
                'message' => 'JSON is valid at creation time',
            ]);
        }

        $formatter = new ErrorFormatter();

        return response()->json([
            'success' => false,
            'message' => 'JSON failed validation at creation time',
            'errors' => $formatter->format($result->error()),
        ], 422);
    }

    public function validateConsumption()
    {
        $schemaPath = storage_path('app/schemas/service.schema.json');
        $jsonPath = storage_path('app/json/service.json');

        if (!File::exists($schemaPath) || !File::exists($jsonPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Schema or JSON file not found',
            ], 404);
        }

        $schemaContent = File::get($schemaPath);
        $jsonContent = File::get($jsonPath);

        $schema = json_decode($schemaContent);
        $data = json_decode($jsonContent);

        if ($schema === null && trim($schemaContent) !== 'null') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid JSON in service.schema.json',
                'error' => json_last_error_msg(),
            ], 422);
        }

        if ($data === null && trim($jsonContent) !== 'null') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid JSON in service.json',
                'error' => json_last_error_msg(),
            ], 422);
        }

        $validator = new Validator();
        $result = $validator->validate($data, $schema);

        if ($result->isValid()) {
            return response()->json([
                'success' => true,
                'message' => 'JSON is valid at consumption time',
                'data' => $data,
            ]);
        }

        $formatter = new ErrorFormatter();

        return response()->json([
            'success' => false,
            'message' => 'JSON failed validation at consumption time',
            'errors' => $formatter->format($result->error()),
        ], 422);
    }
}