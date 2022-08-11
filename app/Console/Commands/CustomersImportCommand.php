<?php

namespace App\Console\Commands;

use App\Exports\CustomersExport;
use App\Imports\CustomersImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

    private $filename = 'РНР_random.csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import customers from csv';


    public function handle()
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $importer = new CustomersImport();
        if (!Storage::disk('public')->exists($this->filename)) {
            $out->writeln("file " . $this->filename . ' not found');
            return;
        }
        $importer->import(Storage::disk('public')->path($this->filename));
        $filename = 'customers_error.xlsx';
        $file = Excel::store(new CustomersExport($importer->getImportedCount()), 'public/'.$filename);
        $path = substr(storage_path($file), 0, -1) . $filename;
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln($path);
    }
}
