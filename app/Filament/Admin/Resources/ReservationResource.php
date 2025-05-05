<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReservationResource\Pages;
use App\Filament\User\Resources\ReservationResource\Pages\CreateReservation;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Table as TableModel;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->native(false)
                            ->minDate(now()->format('Y-m-d'))
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                if (TableModel::query()->isAvailable($state)->doesntExist()) {
                                    Notification::make()
                                        ->title('Table not available')
                                        ->body('The table is not available for the selected date.')
                                        ->color('danger')
                                        ->send();
                                }
                            }),

                        Forms\Components\Select::make('time')
                            ->options([
                                '12:00' => '12:00',
                                '13:00' => '13:00',
                                '14:00' => '14:00',
                                '15:00' => '15:00',
                                '16:00' => '16:00',
                                '17:00' => '17:00',
                                '18:00' => '18:00',
                                '19:00' => '19:00',
                                '20:00' => '20:00',
                                '21:00' => '21:00',
                                '22:00' => '22:00',
                                '23:00' => '23:00',
                            ])
                            ->required(),
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('table')
                            ->relationship('table', 'name', fn ($query, Forms\Get $get) => 
                                $query->isAvailable($get('date'))
                            )
                            ->getOptionLabelFromRecordUsing(fn (TableModel $record) => 
                                "{$record->name} ({$record->seat_count} seats)"
                            )
                            ->rules([
                                fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $exists = TableModel::isAvailable($get('date'))
                                        ->where('id', $value)
                                        ->exists();

                                    if (!$exists) {
                                        $fail('The table is not available for the selected date.');
                                    }
                                },
                            ])
                            ->exists(TableModel::class, 'id')
                            ->key('table')
                            ->live()
                            ->required()
                            ->dehydratedWhenHidden(),

                        Forms\Components\TextInput::make('number_of_people')
                            ->required()
                            ->numeric()
                            ->maxValue(function(Forms\Components\TextInput $component) {
                                return $component->getContainer()->getComponent('table')->getSelectedRecord()?->seat_count;
                            }),
                ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('user')
                            ->relationship('user', 'name')
                            ->required()
                            ->exists(User::class, 'id')
                            ->hiddenOn(CreateReservation::class)
                            ->createOptionForm(fn (Form $form) => UserResource::form($form)),

                        PhoneInput::make('phone')
                            ->required()
                            ->validateFor('AUTO', null, false),

                        Forms\Components\Textarea::make('comment')
                            ->hiddenOn(Pages\CreateReservation::class)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('table.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->description(fn (Reservation $record) => $record->user->email)
                    ->sortable(),
                PhoneColumn::make('phone')
                    ->displayFormat(PhoneInputNumberType::INTERNATIONAL),
                Tables\Columns\TextColumn::make('table.name')
                    ->sortable()
                    ->hiddenOn(Pages\ListReservations::class),
                Tables\Columns\TextColumn::make('date_time')
                    ->state(fn (Reservation $record) => $record->date->format('Y-m-d') . ' ' . $record->time)
                    ->dateTime()
                    ->badge(),
                Tables\Columns\TextColumn::make('number_of_people'),
                Tables\Columns\TextColumn::make('comment')
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->query(function ($query, array $data) {
                        if ($data['date']) {
                            $date = Carbon::parse($data['date']);

                            return $query->where('date', $date);
                        }
                    })
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->native(false)
                            ->live(),
                    ]),
            ], layout: FiltersLayout::AboveContent)
            ->recordAction(null)
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
        ];
    }
}
