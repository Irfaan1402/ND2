<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meetings';

    public function scopeOrderByDate($query)
    {
        $query->orderBy('date');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('date', 'like', '%'.$search.'%');
            });
        })->when($filters['office_id'] ?? null, function ($query, $office) {
            $query->where(function ($query) use ($office) {
                $query->where('office_id',$office);
            });
        })->when($filters['date'] ?? null, function ($query, $date) {
            $query->where(function ($query) use ($date) {
                $query->where('date',$date);
            });
        });
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'meeting_has_member', 'meeting_id', 'member_id');
    }

}
