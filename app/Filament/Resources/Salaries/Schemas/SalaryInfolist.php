<?php

namespace App\Filament\Resources\Salaries\Schemas;

use App\Models\Salary;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SalaryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('role.name')
                    ->color('info'),
                TextEntry::make('price')
                    ->label('Gaji')
                    ->money('IDR', locale: 'id'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(Salary $record): bool => $record->trashed()),
            ]);
    }
}
