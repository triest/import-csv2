<?php

namespace App\Imports;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class CustomersImport implements ToModel, WithCustomCsvSettings, WithHeadingRow
{

    use Importable, SkipsFailures;

    protected array $importedCount = [
            'imported' => 0,
            'failed' => 0,
            'entity_fails' => [],
    ];

    public $rowCount = 0;

    /**
     * @return array|int[]
     */
    public function getImportedCount()
    {
        return $this->importedCount;
    }

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

    private $rules = array(
            'email' => 'email',
    );

    private $rulesLocation = ['location' => 'max:255'];

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules);
        // return the result
        return $v;
    }


    public function validateLocation($data)
    {
        $v = Validator::make($data, $this->rulesLocation);
        return $v;
    }

    /**
     * @param array $row
     *
     * @return Customer
     */
    public function model(array $row)
    {
        $this->rowCount++;
        if ($row['age'] < 18 && $row['age'] > 99) {
            $this->importedCount['entity_fails'][]['value'] = $row;
            $this->importedCount['entity_fails'][]['error'] = 'age';
        }

        if (!$this->validate($row)->passes()) {
            $this->importedCount['entity_fails'][]['value'] = $row;
            $this->importedCount['entity_fails'][]['error'] = 'email';
        }

        //validate location

        if (!$this->validateLocation($row)->passes()) {
            $row['location'] = 'Unknown';
        }

        $dateOfBirth = Carbon::now()->subYears(intval($row['age']))->toDateString();

        $this->importedCount['imported']++;


        return Customer::updateOrCreate(
                ['id' => $row['id']],
                [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'location' => $row['location'],
                        'age' =>strval($dateOfBirth),
                        'email' => $row['email']
                ]
        );
    }

    public function prepare()
    {
    }
    /*
        public function rules(): array
        {
            return  [
                    'name' => 'string',
                    'email' => 'string|email:rfc,dns',
                    'age' => 'min:18|max:99'
            ];
        }
    */

}
