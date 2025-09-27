<?php

namespace App\Filament\Resources\Outcomes\Pages;

use App\Filament\Resources\Outcomes\OutcomeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOutcome extends ViewRecord
{
    protected static string $resource = OutcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
