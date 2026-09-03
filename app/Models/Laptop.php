<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laptop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'brand', 'model', 'serial_number', 'processor',
        'ram_gb', 'storage_gb', 'price', 'currency',
        'purchase_date', 'status',
    ];

    /** Selectable states for a laptop in the fleet. */
    public const STATUSES = ['available', 'assigned', 'repair', 'retired'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
