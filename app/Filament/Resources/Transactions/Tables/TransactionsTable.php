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
                    ->label("Type")
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
                    ->label("Buyer")
                    ->description(fn(Transaction $record): string => $record->buyer_phone)
                    ->searchable(),
                TextColumn::make('product_amount')
                    ->label("Price")
                    ->money('IDR', locale: 'id')
                    ->description(fn(Transaction $record): string => $record->product_count . "x")
                    ->sortable(),
                TextColumn::make('Income')
                    ->getStateUsing(function ($record) {
                        $amount = $record->product_amount;
                        $count = $record->product_count;

                        return $amount * $count;
                    })
                    ->money(currency: 'IDR', locale: 'id')
                    ->color("warning")
                    ->sortable(),
                TextColumn::make('acrylic_mm')
                    ->label("Acrylic MM")
                    ->suffix("mm")
                    ->numeric()
                    ->sortable(),
                TextColumn::make('notes')
                    ->default('-')
                    ->searchable(),
                TextColumn::make('order_date')
                    ->label("Order Date")
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
