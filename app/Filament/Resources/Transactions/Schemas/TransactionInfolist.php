<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Transaction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make(name: 'user.name')
                    ->color("info")
                    ->label("Admin"),
                TextEntry::make(name: 'category.name')
                    ->label("Kategori"),
                TextEntry::make(name: 'color.name')
                    ->label("Warna"),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('buyer_name')
                    ->label("Nama Pembeli"),
                TextEntry::make('buyer_phone')
                    ->label("Kontak Pembeli")
                    ->prefix('62')
                    ->url(fn(Transaction $record): string => 'https://wa.me/62' . $record->buyer_phone)
                    ->openUrlInNewTab(),
                TextEntry::make('product_amount')
                    ->label("Harga Produk")
                    ->numeric(),
                TextEntry::make('product_count')
                    ->label("Jumlah Produk")
                    ->prefix('x')
                    ->numeric(),
                TextEntry::make('acrylic_mm')
                    ->label("Akrilik")
                    ->suffix('mm')
                    ->numeric(),
                TextEntry::make('notes')
                    ->label("Catatan"),
                ImageEntry::make('attachments'),
                TextEntry::make('order_date')
                    ->label("Tanggal Pemesanan")
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(Transaction $record): bool => $record->trashed()),
            ]);
    }
}
