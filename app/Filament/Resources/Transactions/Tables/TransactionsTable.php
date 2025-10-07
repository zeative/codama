<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Models\Category;
use App\Models\Color;
use App\Models\Transaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'user.name')
                    ->color("info")
                    ->label("Admin"),
                TextColumn::make('category.name')
                    ->label("Detail")
                    ->description(fn(Transaction $record): string => $record->color->name)
                    ->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'progress' => 'Progress',
                        'done' => 'Done',
                        'cancel' => 'Cancel'
                    ]),
                TextColumn::make('buyer_name')
                    ->label("Pembeli")
                    ->description(fn(Transaction $record): string => '62' . $record->buyer_phone)
                    ->url(fn(Transaction $record): string => 'https://wa.me/62' . $record->buyer_phone)
                    ->openUrlInNewTab()
                    ->searchable(),
                TextColumn::make('product_amount')
                    ->label("Harga")
                    ->money('IDR', locale: 'id')
                    ->description(fn(Transaction $record): string => $record->product_count . "x")
                    ->sortable(),
                TextColumn::make('Pemasukan')
                    ->getStateUsing(function ($record) {
                        $amount = $record->product_amount;
                        $count = $record->product_count;

                        return $amount * $count;
                    })
                    ->money(currency: 'IDR', locale: 'id')
                    ->color("warning")
                    ->sortable(),
                TextColumn::make('acrylic_mm')
                    ->label("Akrilik")
                    ->suffix("mm")
                    ->numeric()
                    ->sortable(),
                TextColumn::make('notes')
                    ->label("Catatan")
                    ->default('-')
                    ->searchable(),
                TextColumn::make('order_date')
                    ->label("Tanggal Pemesanan")
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order_date', direction: 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ])
            ]);
    }
}
