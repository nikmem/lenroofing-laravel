<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
	public function upload(Request $request)
	{
		$request->validate([
			'file' => 'required|file'
		]);

		if ($request->hasFile('file')) {
			$file = $request->file('file');
			list('path' => $path, 'size' => $size) = fileStore($file, 'files');

			return response()->json([
				'message' => 'File uploaded successfully',
				'path' => $path
			], 200);
		}

		return response()->json(['message' => 'No file uploaded'], 400);
	}

	public function analyze(Request $request)
	{
		// Placeholder URLs for the APIs
		$apiUrls = [
			'http://66.42.119.174:8000/api/analyze',
			'http://66.42.119.174:8000/api/results',
			'http://66.42.119.174:8000/api/active-estimate',
		];

		try {
			// return $request;
			$filename = basename($request->url);

			$messageId = pathinfo($filename, PATHINFO_FILENAME);
			$userId = pathinfo($filename, PATHINFO_FILENAME);

			//\Str::random(10); // Generates a 10-character random string


			// First API call (POST)
			$postData = [
				"url" => $filename,
				"message_id" => $messageId,
				"user_id" => $userId
			];
			$response1 = $this->makeCurlRequest('POST', $apiUrls[0], $postData);

			// Second API call (GET)
			$response2 = $this->makeCurlRequest('GET', $apiUrls[1] . '?user_id=' . $userId . '&message_id=' . $messageId);

			// Third API call (GET)
			$response3 = $this->makeCurlRequest('GET', $apiUrls[2] . '?user_id=' . $userId . '&estimate_ids=' . $response2['estimate_ids'][0]);

			return response()->json([
				'message' => 'Analyze completed successfully',
				'final_result' => $response3
			], 200);

		} catch (\Exception $e) {
			return response()->json([
				'message' => 'An error occurred during analysis',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Make a cURL request
	 * 
	 * @param string $method The HTTP method ('GET', 'POST', etc.)
	 * @param string $url The API endpoint
	 * @param array|null $data The request payload (for POST requests)
	 * @return array The decoded JSON response
	 * @throws \Exception if the request fails or returns an error
	 */
	private function makeCurlRequest(string $method, string $url, array $data = null): array
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Accept: application/json',
			'x-api-key: p0xm34aniojkdlw933kzxmc140kdjzp',
		]);

		if ($method === 'POST') {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			throw new \Exception('cURL error: ' . curl_error($ch));
		}

		curl_close($ch);

		$decodedResponse = json_decode($response, true);
		if ($httpCode >= 400 || !$decodedResponse) {
			throw new \Exception('HTTP error ' . $httpCode . ': ' . $response);
		}

		return $decodedResponse;
	}
}
