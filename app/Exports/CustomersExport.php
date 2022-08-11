<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;


class CustomersExport implements FromCollection
{
    public array $collection;

    /**
     * CustomersExport constructor.
     * @param Collection $array
     */
    public function __construct(array $array)
    {
        $this->collection = $array;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       // var_dump($this->collection); die();

        $entity_fails= $this->collection['entity_fails'];
      //  var_dump($this->collection->entity_fails); die();
        $result = [];

        $count = 2;
        $result[0] =$this->getHeader();
        $count2 = 1;

        foreach ($entity_fails as $item){
            foreach ($item['value'] as $tempItem){
                $result[$count][$count2]=$tempItem;
                $count2++;
            }
            $result[$count][$count2] = $item['error'];
            $count2=1;
            $count++;
        }

        return  collect($result);
    }


    public function getHeader(){
        $headers_array = array_keys(array_shift($this->collection['entity_fails'])['value']);
        $headers_array[] = 'error';
        return $headers_array;
    }
}
