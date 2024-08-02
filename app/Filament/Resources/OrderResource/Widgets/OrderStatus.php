<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStatus extends BaseWidget
{
    /**
     *  this widget will display the count of orders with status pending on the list page of the order resource
     * @return array of stats to be displayed on the list page
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Orders', Order::query()->where('status', 'pending')->count()),  // get the count of orders with status pending
            Stat::make('Shipped Orders', Order::query()->where('status', 'shipped')->count()), // get the count of orders with status shipped
            Stat::make('Delivered Orders', Order::query()->where('status', 'delivered')->count()), // get the count of orders with status delivered
            Stat::make('Total net profit', Number::currency(Order::query()->sum('total'), 'BHD')), // get the total price of all orders
            // Stat::make('Average net profit', Number::currency(Order::query()->avg('total'), 'BHD')), // get the average price of all orders
        ];
    }
}
