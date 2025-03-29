<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'tbluser';
    protected $fillable = ['username', 'password', 'gender'];
    public $timestamps = true; // If using created_at and updated_at
}

