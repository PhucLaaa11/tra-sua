<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class CleanProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:clean-images';

    /**
     * The console command description.
     *
     * @var string
     */
    // Translate: "Delete image files in storage/public/products that are no longer referenced in the DB"
    protected $description = 'Delete image files in storage/public/products not referenced in the DB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Translate: "Get list of images from DB"
        $dbImages = Product::pluck('image')->filter()->toArray();

        // Translate: "Get list of files in storage"
        $storageImages = Storage::disk('public')->files('products');

        $deletedCount = 0;

        foreach ($storageImages as $file) {
            if (!in_array($file, $dbImages)) {
                Storage::disk('public')->delete($file);
                // Translate: "Deleted orphaned file: $file"
                $this->info("Deleted orphaned file: $file");
                $deletedCount++;
            }
        }

        // Translate: "Completed! Deleted ... orphaned files."
        $this->info("✅ Completed! Deleted {$deletedCount} orphaned files.");
    }   
}