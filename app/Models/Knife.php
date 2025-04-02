<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knife extends Model
{
    use HasFactory;

    // Разрешаем массовое присваивание для этих полей
    protected $fillable = ['name', 'description', 'price', 'image_url'];

}
