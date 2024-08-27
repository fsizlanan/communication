<?php

namespace App\Http\Controllers;

use Google\Service\Drive\Drive;
use Google_Client;
use Google_Service;
use Illuminate\Http\Request;

class WeeklyGoogleSheetsController extends Controller
{
    public function exportToGoogleSheets()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/credentials.json'));
        $client->addScope(Drive::DRIVE);

        $service = new Google_Service($client);

        $spreadsheetId = env('SPREADSHEET_ID');
        $range = env('RANGE');

        $data = [
            ['Week', 'Value'],
            ['Week 1', 123],
            ['Week 2', 456],
        ];

        $body = new Google_Service([
            'values' => $data
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);

        return response()->json($result);
    }
}
