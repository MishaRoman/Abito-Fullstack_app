<?php 

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImagesService
{
	protected static string $dir = 'images/';

	public static function saveImage(string $image): string
	{
		$image = self::encodeImage($image);

        $file = Str::random() . '.' . $image['type'];
        $relativePath = self::$dir . $file;

        file_put_contents($relativePath, $image['data']);

        return $relativePath;
	}

    public static function deleteImage(string $image)
    {
        $absolutePath = public_path($image);
        File::delete($absolutePath);
    }

	public static function encodeImage(string $image): array
	{
		// Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]);

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if (!$image) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        return ['data' => $image, 'type' => $type];
	}
}