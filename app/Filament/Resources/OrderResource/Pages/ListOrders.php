<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets\OrderStatus;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * function to display the widgets on the list page of the order resource
     * @return array of widgets to be displayed on the list page from the OrderStatus widget file
     */
    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatus::class,
        ];
    }

    /**
     * function to display the tabs on the list page of the order resource
     * @return array of tabs to be displayed on the list page
     * @return null to display all orders
     * @return pending to display orders with status pending
     */
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'pending' => Tab::make()->query(fn ($query) => $query->where('status', 'pending')),
            'shipped' => Tab::make()->query(fn ($query) => $query->where('status', 'shipped')),
            'delivered' => Tab::make()->query(fn ($query) => $query->where('status', 'delivered')),
            'declined' => Tab::make()->query(fn ($query) => $query->where('status', 'declined')),
            'canceled' => Tab::make()->query(fn ($query) => $query->where('status', 'canceled')),
        ];
    }
}
