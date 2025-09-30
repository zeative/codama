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
                    ->label("Nama Barang")
                    ->required(),
                TextInput::make('description')
                    ->label("Deskripsi")
                    ->required(),
                TextInput::make('price')
                    ->label("Harga Barang")
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                DatePicker::make('date_outcome')
                    ->label("Tanggal Pembelian")
                    ->default(CarbonImmutable::now())
                    ->required(),
                FileUpload::make('file')
                    ->label("Bukti Foto")
                    ->required(),
            ]);
    }
}
