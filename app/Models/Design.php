<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "name",
        "description",
        "is_finish",
        "file"
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($design) {
            if (Auth::check() && !$design->user_id) {
                $design->user_id = Auth::id();
            }
        });
    }
}
