<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FoodItem;
use Illuminate\Support\Facades\DB;

class ImportFoodItems extends Command
{
    protected $signature = 'import:food-items';
    protected $description = 'Import food items from CSV file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = fopen(storage_path('app/ingredients_metadata.csv'), 'r');
        fgetcsv($file); // Skip the header row

        while (($data = fgetcsv($file)) !== FALSE) {
            DB::table('food_items')->insert([
                'food_name' => $data[0],
                'cal_per_g' => $data[1],
                'fat_per_g' => $data[2],
                'carb_per_g' => $data[3],
                'protein_per_g' => $data[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);

        $this->info('Food items imported successfully!');
    }
}
