<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class StudentDirectoryService
{
    public function findStudent(string $enrollmentNo)
    {
        $sheetId = env('STUDENTS_SHEET_ID');
        if (!$sheetId) return false;

        $url = "https://docs.google.com/spreadsheets/d/{$sheetId}/gviz/tq?tqx=out:json";
        
        try {
            $response = Http::get($url);
            $json = substr($response->body(), 47, -2);
            $data = json_decode($json, true);
            $rows = $data['table']['rows'] ?? [];
            
            foreach ($rows as $row) {
                // Col A = Enrollment, Col B = Semester
                $enrollment = strtoupper(trim($row['c'][0]['v'] ?? ''));
                $semester = (int)($row['c'][1]['v'] ?? 1);
                
                if ($enrollment === strtoupper(trim($enrollmentNo))) {
                    return ['semester' => $semester];
                }
            }
        } catch (\Exception $e) {}
        return false;
    }

    public function verifyAdmin(string $username, string $password)
    {
        $sheetId = env('ADMINS_SHEET_ID');
        if (!$sheetId) return false;

        $url = "https://docs.google.com/spreadsheets/d/{$sheetId}/gviz/tq?tqx=out:json";
        
        try {
            $response = Http::get($url);
            $json = substr($response->body(), 47, -2);
            $data = json_decode($json, true);
            $rows = $data['table']['rows'] ?? [];
            
            foreach ($rows as $row) {
                // Col A = Username, Col B = Password
                $sheetUser = trim($row['c'][0]['v'] ?? '');
                $sheetPass = trim($row['c'][1]['v'] ?? '');
                
                if (strtolower($sheetUser) === strtolower(trim($username)) && $sheetPass === trim($password)) {
                    return true;
                }
            }
        } catch (\Exception $e) {}
        return false;
    }
}