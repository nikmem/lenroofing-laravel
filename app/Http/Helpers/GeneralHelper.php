<?php

use App\Models\Notification;

use Illuminate\Support\Str;


function storeNotification($type, $estimate)
{
	$name = request()->user()->name;
	$text = '';
	if ($type === 'Completed') {
		$text = "{$name} marked estimate ({$estimate->name}) as Completed";
	} elseif ($type === 'In Complete') {
		$text = "{$name} marked estimate ({$estimate->name}) as In Complete";
	} elseif ($type === 'Urgent') {
		$text = "{$name} marked estimate ({$estimate->name}) as Urgent";
	} elseif ($type === 'Not Urgent') {
		$text = "{$name} marked estimate ({$estimate->name}) as Not Urgent";
	} elseif ($type === 'Archived') {
		$text = "{$name} marked estimate ({$estimate->name}) as Archived";
	} elseif ($type === 'Unarchived') {
		$text = "{$name} marked estimate ({$estimate->name}) as Unarchived";
	} elseif ($type === 'Deleted') {
		$text = "{$name} deleted estimate ({$estimate->name})";
	} elseif ($type === 'Created') {
		$text = "{$name} created estimate ({$estimate->name})";
	}

	Notification::store($text, $estimate->id);
}



function createUniqueSlug($title)
{
	// Generate the initial slug
	$slug = Str::slug($title, '-');

	// Check if the slug already exists
	$count = Blog::where('slug', 'LIKE', "{$slug}%")->count();

	// If a similar slug already exists, append the count to make it unique
	return $count ? "{$slug}-{$count}" : $slug;
}

function formatToTwoDecimalPlaces($number)
{
	return number_format($number, 2);
}
function removeNonNumericChars($inputString)
{
	// Use regular expression to remove non-numeric characters
	$outputString = preg_replace('/[^0-9]/', '', $inputString);

	return $outputString;
}

function getRandomString($n)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';

	for ($i = 0; $i < $n; $i++) {
		$index = rand(0, strlen($characters) - 1);
		$randomString .= $characters[$index];
	}

	return $randomString;
}
function convertTimeFormat($dateString)
{
	$timestamp = strtotime($dateString);

	if ($timestamp !== false) {
		$formattedDate = date("Y-m-d H:i:s", $timestamp);
		return $formattedDate;
	}
	return now();
}

function checkIfHtmlEmpty($htmlContent)
{
	// Remove HTML tags and trim whitespace
	$cleanedContent = trim(strip_tags($htmlContent));

	// Check if the cleaned content is empty
	if (empty($cleanedContent)) {
		return true;
	}
	return false;
}

function fileStore($file, $folder = 'images', $custom_name = null)
{
	if (!$file->isValid()) {
		throw new \Exception('Invalid uploaded file');
	}

	$file_info = $file->getClientOriginalName();
	$file_name = pathinfo($file_info, PATHINFO_FILENAME);
	$extension = pathinfo($file_info, PATHINFO_EXTENSION);


	if (is_null($custom_name))
		$file_name = $file_name . '-' . time() . '.m4a';
	else
		$file_name = $custom_name;

	// $destinationPath = storage_path('app/public/' . $folder);
	$destinationPath = '/var/www/html/roofbuddy/audio_files';

	if (!is_dir($destinationPath)) {
		mkdir($destinationPath, 0755, true);
	}

	$file->move($destinationPath, $file_name);

	$file_path = '/' . $file_name;
	$file_size = filesize($destinationPath . '/' . $file_name);


	return [
		'path' => '/storage' . $file_path,
		'size' => $file_size,
	];
}




