<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ColorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('merk')
                    ->label('Merek Cat')
                    ->default(null),
                Toggle::make('is_available')
                    ->default(true)
                    ->label("Tersedia")
                    ->required(),
            ]);
    }
}
