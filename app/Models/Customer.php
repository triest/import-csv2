<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $casts = [
            'age' => 'datetime:Y-m-d',
    ];

    protected $fillable =['id','name','email','age','location'];
}
