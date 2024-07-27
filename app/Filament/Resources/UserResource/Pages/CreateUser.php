<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // this function should redirect to the index page after creating a record
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
