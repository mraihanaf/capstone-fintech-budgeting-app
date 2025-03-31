<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'action', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilters($query, array $filters)
    {
        return $query
            ->when($filters['sort_by'] ?? null, fn($q) => $q->orderBy($filters['sort_by']))
            ->when($filters['sort_order'] ?? null, fn($q) => $q->orderBy($filters['sort_by'] ?? 'id', $filters['sort_order']));
    }
}
