<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilesystemPublicDiskTest extends TestCase
{
    public function test_public_disk_writes_directly_to_public_storage_folder(): void
    {
        $disk = Storage::disk('public');
        $path = 'hosting-safe-test.txt';

        $this->assertSame(rtrim(public_path(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR, $disk->path(''));

        $disk->put($path, 'hosting-safe');

        $this->assertFileExists(public_path($path));

        unlink(public_path($path));
    }
}
