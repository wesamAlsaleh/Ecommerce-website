<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;



    // this function should redirect to the index page after creating a record
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
