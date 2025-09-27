<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Transaction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('category_id')
                    ->numeric(),
                TextEntry::make('color_id')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('buyer_name'),
                TextEntry::make('buyer_phone')
                    ->numeric(),
                TextEntry::make('product_amount')
                    ->numeric(),
                TextEntry::make('product_count')
                    ->numeric(),
                TextEntry::make('acrylic_mm')
                    ->numeric(),
                TextEntry::make('notes'),
                TextEntry::make('order_date')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Transaction $record): bool => $record->trashed()),
            ]);
    }
}
