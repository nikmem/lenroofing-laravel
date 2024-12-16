<?php
namespace App\Http\Helpers;
use Illuminate\Http\Request;

class UploadFiles extends Helper
{

	public static function upload($image, string $fieldName, string $folder)
	{

		if ($image && $image->isValid()) {
			$image = $image;
			$imageName = time() . '.' . $image->getClientOriginalExtension();
			$image->storeAs($folder, $imageName, 'public');
			$imageUrl = '/storage/' . $folder . '/' . $imageName;
			return $imageUrl;
		}
		return null;
	}

	public static function uploadFile($file)
	{
		$path = $file->storeAs('audios', $file->getClientOriginalName(), 'public');
		$baseUrl = url('/');
		$filePaths = $baseUrl . '/storage/' . $path;

		// Return the file path
		return $filePaths;
	}

}