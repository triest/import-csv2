<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class CustomersImport implements ToModel, WithValidation, WithCustomCsvSettings,WithHeadingRow
{

    use Importable, SkipsFailures;


    public function headingRow(): int
    {
        return 1;
    }

    public function getCsvSettings(): array
    {
        return [
                'delimiter' => ','
        ];
    }

    /**
     * @param array $row
     *
     * @return Customer
     */
    public function model(array $row)
    {



       return Customer::updateOrCreate(['id' => $row['id']],['id' => $row['id'],
                'name' => $row['name'],
            //        'age' =>  $row['age'],
                'email' => $row['email']]);
    }

    public function rules(): array
    {
        return  [
                'name' => 'string',
                'email' => 'string|email:rfc,dns',
                'age' => 'min:18|max:99'
        ];
    }


}
