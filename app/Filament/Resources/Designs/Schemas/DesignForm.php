<?php

namespace App\Filament\Resources\Designs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

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
                TextInput::make('name')
                    ->required(),
                TextInput::make('notes')
                    ->default(null),
                FileUpload::make('file')
                    ->required(),
                Toggle::make('is_finish')
                    ->label('Finish')
                    ->required(),
            ]);
    }
}
