<?php

namespace App\Filament\Resources\Outcomes;

use App\Filament\Resources\Outcomes\Pages\CreateOutcome;
use App\Filament\Resources\Outcomes\Pages\EditOutcome;
use App\Filament\Resources\Outcomes\Pages\ListOutcomes;
use App\Filament\Resources\Outcomes\Schemas\OutcomeForm;
use App\Filament\Resources\Outcomes\Tables\OutcomesTable;
use App\Models\Outcome;
use BackedEnum, UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutcomeResource extends Resource
{
    protected static ?string $model = Outcome::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWallet;

    protected static ?string $recordTitleAttribute = 'Outcome';

    protected static string|UnitEnum|null $navigationGroup = 'Moderation';

    public static function form(Schema $schema): Schema
    {
        return OutcomeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OutcomesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOutcomes::route('/'),
            'create' => CreateOutcome::route('/create'),
            'edit' => EditOutcome::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
