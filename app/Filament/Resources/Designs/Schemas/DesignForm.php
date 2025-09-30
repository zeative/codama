<?php

namespace App\Filament\Resources\Designs\Schemas;

use App\Models\Design;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class DesignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->components([
                TextInput::make('user_id')
                    ->default(auth()->id())
                    ->hidden()
                    ->dehydrated(false),
                Select::make('transaction_id')
                    ->label('Pilih transaksi')
                    ->relationship(
                        name: 'transactions',
                        titleAttribute: 'buyer_name',
                        modifyQueryUsing: fn(Builder $query, ?Design $record) => $query->whereDoesntHave('designs')->orWhereHas(
                            'designs',
                            fn(Builder $query) => $query->where('design_id', $record?->id)
                        )
                    )
                    ->multiple()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nama File')
                    ->required(),
                Textarea::make('notes')
                    ->label('Catatan'),
                FileUpload::make('file')
                    ->downloadable()
                    ->required(),
                Toggle::make('is_finish')
                    ->label('Selesai')
                    ->required(),
            ]);
    }
}