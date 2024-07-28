<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;

// for the colors Color::NAME
use Filament\Support\Colors\Color;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order information')->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name') // powered by the user relationship (relation name, column name on the related table)
                            ->required()
                            ->label('Customer')
                            ->placeholder('Select the customer')
                            ->searchable()
                            ->preload(),

                        Select::make('payment_method')
                            ->options([
                                // 'paypal' => 'Paypal',
                                'stripe' => 'Stripe',
                                // 'credit_card' => 'Credit Card',
                                'cash_on_delivery' => 'Cash on delivery',
                            ])
                            ->required()
                            ->label('Payment method')
                            ->placeholder('Select the payment method'),

                        Select::make('payment_status') // expected to be string
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required()
                            ->label('Payment status')
                            ->placeholder('Select the payment status'),

                        ToggleButtons::make('status') // type of enum (string)
                            ->options([
                                'pending' => 'Pending',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'declined' => 'Declined',
                                'canceled' => 'Cancelled',
                            ])
                            ->colors([
                                'pending' => Color::Amber,
                                'shipped' => Color::Blue,
                                'delivered' => Color::Green,
                                'declined' => Color::Red,
                                'canceled' => Color::Zinc,
                            ])
                            ->icons(
                                [
                                    'pending' => 'heroicon-o-clock',
                                    'shipped' => 'heroicon-o-truck',
                                    'delivered' => 'heroicon-o-check-circle',
                                    'declined' => 'heroicon-o-x-circle',
                                    'canceled' => 'heroicon-o-x-mark',
                                ]
                            )
                            ->inline()
                            ->default('pending')
                            ->required()
                            ->label('Order status'),

                        Select::make('currency')
                            ->options([
                                'bhd' => 'BHD',
                                'usd' => 'USD',
                                'eur' => 'EUR',
                            ])
                            ->default('bhd')
                            ->label('Currency')
                            ->placeholder('Select the currency'),

                        Select::make('shipping_method')
                            ->options([
                                'standard' => 'Standard',
                                'express' => 'Express',
                            ])
                            ->default('standard')
                            ->label('Shipping method')
                            ->placeholder('Select the shipping method'),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Enter any notes about the order')
                            ->columnSpanFull(),

                    ])->columns(2), // End Section 1

                    Section::make('Order items')->schema([
                        // Repeater for order items (put the order products in the order)
                        Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name') // powered by the product relationship (orderItem model)
                                    ->required()
                                    ->label('Product')
                                    ->placeholder('Select the product')
                                    ->searchable()
                                    ->distinct()
                                    ->preload()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems() // disable the selected product in other order items
                                    ->columnSpan(4)
                                    ->reactive() // reactive to the price of the product
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('price', Product::find($state)?->price ?? 0)) // this function will update the price of the product in the order item by the help of the reactive function
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('total', Product::find($state)?->price ?? 0)), // this function will update the total price of the product in the order item by the help of the reactive function

                                TextInput::make('quantity') // expected to be integer IN ORDER ITEM schema
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required()
                                    ->label('Quantity')
                                    ->placeholder('Enter the quantity')
                                    ->columnSpan(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total', $state * $get('price'))), // get the price of the product and multiply it by the quantity to get the total price of the product in the order item

                                TextInput::make('price') // expected to be decimal IN ORDER ITEM schema (price of the product at the time of order)
                                    ->numeric()
                                    ->required()
                                    ->prefix('BHD')
                                    ->label('Product price')
                                    ->placeholder('Product price')
                                    ->columnSpan(3),

                                TextInput::make('total') // expected to be decimal IN ORDER ITEM schema (total price of the product in the order)
                                    ->numeric()
                                    ->required()
                                    ->prefix('BHD')
                                    ->label('Total price')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(4),
                            ])->columns(12) // End Repeater schema (Order items) 

                    ]), // End Section 2
                ])->columnSpanFull() // End Group 1
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_fee')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
