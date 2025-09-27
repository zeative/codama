<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Salary extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "role_id",
        "price"
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
