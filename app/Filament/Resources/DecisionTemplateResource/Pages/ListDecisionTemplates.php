<?php

namespace App\Filament\Resources\DecisionTemplateResource\Pages;

use App\Filament\Resources\DecisionTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDecisionTemplates extends ListRecords
{
    protected static string $resource = DecisionTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
