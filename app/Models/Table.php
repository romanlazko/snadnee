<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'seat_count',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Table $table) {
            $table->reservations()->delete();
        });
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeIsAvailable(Builder $query, $date): Builder
    {
        $date = \Carbon\Carbon::parse($date);

        return $query->whereDoesntHave('reservations', function (Builder $query) use ($date) {
            $query->where('date', $date);
        });
    }
}
