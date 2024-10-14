<?php

namespace App\Filament\App\Resources\Product\UserProductResource\Pages;

use App\Filament\App\Resources\Product\UserProductResource;
use App\Services\MoneyService\Money;
use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUserProduct extends ViewRecord
{
    protected static string $resource = UserProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }



    public function infolist(Infolist $infolist): Infolist
    {
        return parent::infolist($infolist)
            ->schema([

                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Details')
                            ->schema([
                                // ...
                            ]),
                        Tabs\Tab::make('Order')
                            ->schema([

                                Section::make('Order Details')
                                    ->aside()
                                    ->columns()
                                    ->schema([

                                        TextEntry::make('order.uuid'),
                                        TextEntry::make('order.amount')->money(Money::defaultCurrency()),

                                        TextEntry::make('order.subtotal')->money(Money::defaultCurrency()),
                                        TextEntry::make('order.discount')->money(Money::defaultCurrency()),
                                        TextEntry::make('order.tax')->money(Money::defaultCurrency()),
                                        TextEntry::make('order.total')->money(Money::defaultCurrency()),

                                        TextEntry::make('order.quantity'),
                                        TextEntry::make('order.voucher'),
                                        TextEntry::make('order.tracking_id'),
                                        TextEntry::make('order.status'),

                                        IconEntry::make('order.payment_success')->default(false),
                                        TextEntry::make('order.expire_at')->since(),

                                    ]),



                                Section::make('Client Details')
                                    ->aside()
                                    ->schema([

                                        TextEntry::make('user.name'),
                                        TextEntry::make('user.email'),

                                    ]),


                            ]),
                        Tabs\Tab::make('Product')
                            ->schema([
                                Section::make('Product Details')
                                    ->aside()
                                    ->schema([

                                        TextEntry::make('product.name')->label(__('Name')),
                                        TextEntry::make('product.type')->label(__('Type'))->badge(),
                                        TextEntry::make('product.short_desc')->alignJustify(),

                                    ]),
                            ]),
                    ]),

            ]);
    }


}
