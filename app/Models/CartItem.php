<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'knife_id', 'quantity'];

    public function knife() {
        return $this->belongsTo(Knife::class);
    }
}
