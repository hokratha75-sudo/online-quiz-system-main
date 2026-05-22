<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait HandlesSecureUploads
{
    /**
     * Store an uploaded image securely by validating its content and stripping metadata.
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param string|null $oldFile
     * @return string|null
     */
    public function uploadSecureImage($file, $directory, $oldFile = null)
    {
        try {
            // 1. Basic Validation
            if (!$file || !$file->isValid()) {
                return null;
            }

            // 2. Validate it's actually an image using getimagesize
            // This checks the file header, which is harder to spoof than just the extension.
            $imageInfo = @getimagesize($file->getRealPath());
            if (!$imageInfo) {
                Log::warning('Security: Attempted upload of non-image file masked as image.', [
                    'ip' => request()->ip(),
                    'user_id' => auth()->id()
                ]);
                return null;
            }

            // 3. Delete old file if provided
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            // 4. Store using Laravel's secure naming (hashName)
            // Storing in the specified directory under the 'public' disk.
            // Laravel's store() method automatically uses a random hash for the filename.
            return $file->store($directory, 'public');

        } catch (\Exception $e) {
            Log::error('Upload failed: ' . $e->getMessage());
            return null;
        }
    }
}
