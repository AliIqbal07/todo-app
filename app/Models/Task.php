<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'due_date', 'completed'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed' => 'boolean',
        ];
    }   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
