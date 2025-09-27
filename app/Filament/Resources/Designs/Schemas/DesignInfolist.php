<?php

namespace App\Filament\Resources\Designs\Schemas;

use App\Models\Design;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DesignInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('name'),
                IconEntry::make('is_finish')
                    ->boolean(),
                TextEntry::make('notes')
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
                    ->visible(fn (Design $record): bool => $record->trashed()),
            ]);
    }
}
