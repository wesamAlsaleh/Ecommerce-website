<?php

namespace App\Filament\Resources;

// import filament stuff
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Pages\Page;

// import form stuff
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Pages\CreateRecord;

// import table stuff
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkActionGroup; // dropdown bulk action
use Filament\Tables\Actions\ActionGroup; // dropdown action group
use Filament\Tables\Actions\DeleteBulkAction;

// import actions stuff
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;


use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;


use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        // create the user resource form (add, edit user)
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('John Doe'),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->placeholder('user@mail.com'),

                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->default(now()),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state)) // check if not empty
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord) // the password field is mandatory if its create record (create new user)
                    ->placeholder('********'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // the columns that will be displayed in the table
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('email_verified_at')
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class, // add the relation manager to display the orders of the user 
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
