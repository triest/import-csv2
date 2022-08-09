<?php

namespace App\Console\Commands;

use App\Imports\CustomersImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class CustomersImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import customers from csv';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Excel::import(new CustomersImport, storage_path('РНР_random.csv'));
        } catch (ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                Log::error( $failure->row());
                Log::error( $failure->attribute());
                Log::error(print_r($failure->errors(),false));
                Log::error(print_r($failure->values(),false));
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }
    }
}
