<?php

namespace App\Filament\Resources\Colors\Schemas;

use App\Models\Color;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ColorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama'),
                TextEntry::make('merk')
                    ->label('Merek Cat')
                    ->placeholder('-'),
                IconEntry::make('is_available')
                    ->boolean()
                    ->label('Tersedia'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(Color $record): bool => $record->trashed()),
            ]);
    }
}
