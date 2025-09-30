<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GalleryForm
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
                FileUpload::make('files')
                    ->directory('galleries')
                    ->downloadable()
                    ->multiple()
                    ->required(),
            ]);
    }
}
