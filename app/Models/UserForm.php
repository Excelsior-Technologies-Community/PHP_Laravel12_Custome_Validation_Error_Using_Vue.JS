<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserForm extends Model
{
    protected $table = 'user_forms'; // Specify table name

    // Columns that can be mass-assigned
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}
