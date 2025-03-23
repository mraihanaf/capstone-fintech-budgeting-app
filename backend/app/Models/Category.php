<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use HasFactory;
    protected $fillable = ['user_id', 'name', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeFilters($query, array $filters)
    {
        return $query
            ->when($filters['type'] ?? null, fn($q) => $q->where('type', $filters['type']))
            ->when($filters['sort_by'] ?? null, fn($q) => $q->orderBy($filters['sort_by']))
            ->when($filters['sort_order'] ?? null, fn($q) => $q->orderBy($filters['sort_by'] ?? 'id', $filters['sort_order']));
    }
}
