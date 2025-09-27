<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "category_id",
        "color_id",
        "status",
        "buyer_name",
        "buyer_phone",
        "product_amount",
        "product_count",
        "acrylic_mm",
        "notes",
        "order_date",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (Auth::check() && !$transaction->user_id) {
                $transaction->user_id = Auth::id();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}
