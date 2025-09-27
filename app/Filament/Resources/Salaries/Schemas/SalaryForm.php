<?php

namespace App\Filament\Resources\Salaries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class SalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role_id')
                    ->label('Role')
                    ->options(Role::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('price')
                    ->label('Gaji')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
            ]);
    }
}
