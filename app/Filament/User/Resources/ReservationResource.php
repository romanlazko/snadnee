<?php

namespace App\Filament\User\Resources;

use App\Filament\Admin\Resources\ReservationResource as AdminReservationResource;
use App\Filament\User\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return AdminReservationResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return AdminReservationResource::table($table)
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('date', '>=', now()->format('Y-m-d'));
            })
            ->defaultGroup(null)
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
        ];
    }
}
