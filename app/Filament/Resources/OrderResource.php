<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use NumberFormatter;
use App\Models\Order;
use Faker\Core\Number;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color; // for the colors Color::NAME
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder; // for the currency formatter in the placeholder
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Address;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    /**
     * this will be used for reordering the navigation items.
     */
    protected static ?int $navigationSort = 5;

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

                        Select::make('payment_method') // expected to be string
                            ->options([
                                // 'paypal' => 'Paypal',
                                // 'credit_card' => 'Credit Card',
                                'stripe' => 'Stripe',
                                'cashOnDelivery' => 'Cash on delivery',
                            ])
                            ->required()
                            ->label('Payment method')
                            ->placeholder('Select the payment method'),

                        Select::make('payment_status') // expected to be string
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid using stripe',
                                'failed' => 'Failed using stripe, so the payment is cash on delivery',
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

                        Select::make('currency')  // expected to be string
                            ->options([
                                'BHD' => 'BHD',
                                'usd' => 'USD',
                                'eur' => 'EUR',
                            ])
                            ->default('BHD')
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
                                    ->prefix('BHD')
                                    ->label('Product price')
                                    ->placeholder('Product price')
                                    ->dehydrated()
                                    ->columnSpan(3),

                                TextInput::make('total') // expected to be decimal IN ORDER ITEM schema (total price of the product in the order)
                                    ->numeric()
                                    ->required()
                                    ->prefix('BHD')
                                    ->label('Total price')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(4),
                            ])->columns(12), // End Repeater schema (Order items)


                        // the total price of the order under the order items repeater
                        Placeholder::make('Order total price:')
                            ->content(function (Get $get, Set $set) {
                                $totalPrice = 0;

                                // if the repeater items are not found, return the total price which is 0
                                if (!$repeaterItems = $get('orderItems')) {
                                    return $totalPrice; // if no products in the order, return 0
                                }

                                // loop through the order items and get the total price of the order
                                foreach ($repeaterItems as $repeaterItem => $value) {
                                    $totalPrice += $get("orderItems.{$repeaterItem}.total"); // get the total price of the product in the order
                                }


                                // return the total price of the order to the hidden field
                                $set('total', $totalPrice);

                                return (new NumberFormatter('en_BH', NumberFormatter::CURRENCY))->formatCurrency($totalPrice, 'BHD'); // Return the total price formatted as BHD currency
                            }), // End Placeholder


                        // Hidden field to store the total price of the order and send it to the database (it will be used to store the total price of the order)
                        Hidden::make('total')
                            ->default(0), // End Hidden

                    ]), // End Section 2
                ])->columnSpanFull() // End Group 1
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Sort by created_at column in descending order
            ->columns([
                TextColumn::make('user.name') // powered by the user relationship in the order model
                    ->sortable()
                    ->searchable()
                    ->label('Customer'),

                // TextColumn::make('first_order')
                //     ->getStateUsing(function ($record) {
                //         return $record->orderItems->first()->product->name;
                //     }),

                TextColumn::make('total')
                    ->label('Total price')
                    ->numeric()
                    ->sortable()
                    ->money(),

                TextColumn::make('payment_method')
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                    }),

                TextColumn::make('currency')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('shipping_method')
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'declined' => 'Declined',
                        'canceled' => 'Cancelled',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),

                ]),
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
            AddressRelationManager::class, // add the address relation manager to the order resource
        ];
    }

    /**
     * get the total number of orders to display in the navigation badge
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count(); // get the total number of orders in the database and display it in the navigation badge
    }

    /**
     * change the navigation badge color
     */
    public static function getNavigationBadgeColor(): string | array | null
    {
        return static::getModel()::count() > 10 ? Color::Green : Color::Red; // if the total number of orders is greater than 10, display the badge in red color, otherwise display it in green color
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
