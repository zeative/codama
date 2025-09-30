<?php

namespace App\Filament\Resources\Designs\Schemas;

use App\Models\Design;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class DesignInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make(name: 'user.name')
                    ->label("Designer")
                    ->color("info"),
                TextEntry::make('name')
                    ->label("Nama File"),
                IconEntry::make('is_finish')
                    ->label("Selesai")
                    ->boolean(),
                TextEntry::make('notes')
                    ->label("Catatan")
                    ->placeholder('-'),
                TextEntry::make('file'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(Design $record): bool => $record->trashed()),
            ]);
    }
}
