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


    public function handle()
    {


          //  $resutr =   Excel::import(new CustomersImport, storage_path('РНР_random.csv'));
          //  var_dump($resutr->getImportedCount);
            $importer = new CustomersImport();
            $importer ->import( storage_path('РНР_random.csv'));

             var_dump(array_merge(
                [  ],
                $importer->getImportedCount()
                      )
        );
    }
}
