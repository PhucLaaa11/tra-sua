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
    protected $description = 'Xóa các file ảnh trong storage/public/products không còn được tham chiếu trong DB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Lấy danh sách ảnh từ DB
        $dbImages = Product::pluck('image')->filter()->toArray();

        // Lấy danh sách file trong storage
        $storageImages = Storage::disk('public')->files('products');

        $deletedCount = 0;

        foreach ($storageImages as $file) {
            if (!in_array($file, $dbImages)) {
                Storage::disk('public')->delete($file);
                $this->info("Đã xóa file rác: $file");
                $deletedCount++;
            }
        }

        $this->info("✅ Hoàn thành! Đã xóa {$deletedCount} file rác.");
    }   
}
