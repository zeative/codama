<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Category;
use App\Models\Color;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->default(auth()->id())
                    ->hidden()
                    ->dehydrated(false),
                Select::make('category_id')
                    ->label('Pilih Kategori')
                    ->options(Category::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('color_id')
                    ->label('Pilih Warna')
                    ->options(Color::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'progress' => 'Progress', 'done' => 'Done', 'cancel' => 'Cancel'])
                    ->default('pending')
                    ->required(),
                TextInput::make('buyer_name')
                    ->label('Nama Pembeli')
                    ->required(),
                TextInput::make('buyer_phone')
                    ->label('Kontak Pembeli')
                    ->prefix('+62')
                    ->tel()
                    ->required()
                    ->numeric(),
                TextInput::make('product_amount')
                    ->label('Harga Produk')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
                TextInput::make('product_count')
                    ->label('Jumlah Produk')
                    ->prefix('x')
                    ->default(1)
                    ->required()
                    ->numeric(),
                TextInput::make('acrylic_mm')
                    ->label('Akrilik')
                    ->suffix('mm')
                    ->default(5)
                    ->required()
                    ->numeric(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->required(),
                DatePicker::make('order_date')
                    ->label('Tanggal Pemesanan')
                    ->default(CarbonImmutable::now())
                    ->required(),
                FileUpload::make('attachments')
                    ->multiple()
                    ->maxParallelUploads(5),
            ]);
    }
}
