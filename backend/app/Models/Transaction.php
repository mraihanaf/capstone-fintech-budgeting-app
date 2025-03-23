<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    use HasFactory;
    protected $fillable = ['user_id', 'category_id', 'amount', 'type', 'description', 'transaction_date', 'is_recurring'];
    protected $with = 'category';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilters($query, array $filters)
    {
        return $query
            ->when($filters['is_recurring'] ?? null, fn($q) => $q->where('is_recurring', $filters['is_recurring']))
            ->when($filters['category_id'] ?? null, fn($q) => $q->where('category_id', $filters['category_id']))
            ->when($filters['type'] ?? null, fn($q) => $q->where('type', $filters['type']))
            ->when($filters['min_amount'] ?? null, fn($q) => $q->where('amount', '>=', $filters['min_amount']))
            ->when($filters['max_amount'] ?? null, fn($q) => $q->where('amount', '<=', $filters['max_amount']))
            ->when($filters['date_range'] ?? null, function ($q) use ($filters) {
                $date = match ($filters['date_range']) {
                    '1day' => Carbon::now()->subDay()->format('Y-m-d'),
                    '3days' => Carbon::now()->subDays(3)->format('Y-m-d'),
                    '1week' => Carbon::now()->subWeek()->format('Y-m-d'),
                    '1month' => Carbon::now()->subMonth()->format('Y-m-d'),
                };

                return $q->where('transaction_date', '>=', $date);
            })
            ->when($filters['sort_by'] ?? null, fn($q) => $q->orderBy($filters['sort_by']))
            ->when($filters['sort_order'] ?? null, fn($q) => $q->orderBy($filters['sort_by'] ?? 'id', $filters['sort_order']));
    }
}
