<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Category;
use App\Models\Color;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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
                    ->label('Category')
                    ->options(Category::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('color_id')
                    ->label('Color')
                    ->options(Color::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'progress' => 'Progress', 'finish' => 'Finish', 'done' => 'Done'])
                    ->default('pending')
                    ->required(),
                TextInput::make('buyer_name')
                    ->label('Buyer Name')
                    ->required(),
                TextInput::make('buyer_phone')
                    ->label('Buyer Phone')
                    ->tel()
                    ->required()
                    ->numeric(),
                TextInput::make('product_amount')
                    ->label('Product Price')
                    ->required()
                    ->numeric(),
                TextInput::make('product_count')
                    ->label('Product Total')
                    ->default(1)
                    ->required()
                    ->numeric(),
                TextInput::make('acrylic_mm')
                    ->label('Acrylic MM')
                    ->default(5)
                    ->required()
                    ->numeric(),
                TextInput::make('notes')
                    ->required(),
                DatePicker::make('order_date')
                    ->label('Order Date')
                    ->default(\Carbon\CarbonImmutable::now())
                    ->required(),
            ]);
    }
}
