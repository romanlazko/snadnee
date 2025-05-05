<?php

namespace App\Models;

use App\Notifications\ReservationSuccessfulyCanceledNotification;
use App\Notifications\ReservationSuccessfulyCreatedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'table_id',
        'phone',
        'date',
        'time',
        'number_of_people',
        'comment',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function booted(): void
    {
        static::created(function (Reservation $reservation) {
            $reservation->user->notify(new ReservationSuccessfulyCreatedNotification($reservation));
        });
        
        static::deleting(function (Reservation $reservation) {
            $reservation->user->notify(new ReservationSuccessfulyCanceledNotification($reservation));
        });
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
