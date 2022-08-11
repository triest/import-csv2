<?php

namespace App\Console\Commands;

use App\Exports\CustomersExport;
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


    public function handle()
    {
        $importer = new CustomersImport();
        $importer->import(storage_path('РНР_random.csv'));
        $filename = 'customers_error.xlsx';
        $file = Excel::store(new CustomersExport($importer->getImportedCount()), $filename);
        $path = substr(storage_path($file), 0, -1) . $filename;
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln($path);
    }
}
