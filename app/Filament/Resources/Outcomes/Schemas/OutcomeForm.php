<?php

namespace App\Filament\Resources\Outcomes\Schemas;

use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OutcomeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->default(auth()->id())
                    ->hidden()
                    ->dehydrated(false),
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                DatePicker::make('date_outcome')
                    ->default(CarbonImmutable::now())
                    ->required(),
                FileUpload::make('file')
                    ->required(),
            ]);
    }
}
