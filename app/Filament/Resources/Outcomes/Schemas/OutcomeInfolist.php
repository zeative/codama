<?php

namespace App\Filament\Resources\Outcomes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OutcomeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make(name: 'user.name')
                    ->label("Admin")
                    ->color("info"),
                TextEntry::make('name')
                    ->label("Nama Barang"),
                TextEntry::make('description')
                    ->label("Deskripsi"),
                TextEntry::make('file')
                    ->label("Bukti Foto"),
                TextEntry::make('date_outcome')
                    ->label("Tanggal Pembelian")
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
