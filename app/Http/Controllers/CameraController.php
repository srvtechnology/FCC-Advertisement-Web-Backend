<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CameraController extends Controller
{


    public function index()
    {
        return view('camera-tool');
    }

    public function analyze(Request $request)
    {

        // dd($request->all());
        $imageFile = $request->file('image');
        $roi = $request->input('roi');
        

        if (!$imageFile || !$roi) {
            return response()->json(['error' => 'Missing image or ROI'], 400);
        }

        try {
            $filename = 'temp_' . time() . '.' . $imageFile->getClientOriginalExtension();
            $storedPath = $imageFile->storeAs('/adss', $filename, 'public');
            $path = storage_path("app/public/" . $storedPath);
            
          


            $curl = curl_init();

            $postFields = [
                'roi' => $roi,
                'unit' => 'ft',
                'marker_width' => '',
                'marker_height' => '',
                'image' => new \CURLFile($path, $imageFile->getMimeType(), $filename),
            ];

            $apiKey = 'DLAI@7879#17';



            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://bb.deeplogicai.tech/detect',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: multipart/form-data',
                    'API-KEY: ' . $apiKey,
                ],
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if (curl_errno($curl)) {
                $error = curl_error($curl);
                curl_close($curl);
                return response()->json(['error' => 'cURL Error: ' . $error], 500);
            }

            curl_close($curl);

            $data = json_decode($response, true);

            // Delete temporary file after use
            if (file_exists($path)) {
                unlink($path);
            }

            if ($httpCode === 200 && json_last_error() === JSON_ERROR_NONE) {
                return response()->json($data);
            } else {
                return response()->json(['error' => $response], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }






}
