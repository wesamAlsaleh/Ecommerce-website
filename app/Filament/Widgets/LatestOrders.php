<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{

    /**
     * Make the widget take the full column span.
     */

    protected int | string | array $columnSpan = 'full';

    /**
     * Make the widget take the second widget of the dashboard page.
     */

    protected static ?int $sort = 2;

    /**
     * Define the widget's table.
     */

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5) // Show 5 records per page
            ->defaultSort('created_at', 'desc') // Sort by created_at column in descending order
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total Amount')
                    ->money()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Order Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                        'declined' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'shipped' => 'heroicon-o-truck',
                        'delivered' => 'heroicon-o-check-circle',
                        'canceled' => 'heroicon-o-x-circle',
                        'declined' => 'heroicon-o-x-circle',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'paid' => 'heroicon-o-check-circle',
                        'failed' => 'heroicon-o-x-circle',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('d-m-Y')
                    ->sortable(),
            ]) // table columns are defined in OrdersRelationManager.php file in UsersResource
            ->actions([
                Tables\Actions\Action::make('View Order')
                    ->label('View Order')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record])), // Redirect to the order detail page
            ]);
    }
}
