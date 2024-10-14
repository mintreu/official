<?php

namespace App\Filament\Resources\Order\OrderResource\Pages;

use App\Filament\Resources\Order\OrderResource;
use App\Services\MoneyService\Money;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }




    public  function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'lg' => 3
            ])
            ->columns([

                Tables\Columns\Layout\Stack::make([

                    Tables\Columns\TextColumn::make('uuid')
                        ->label('Order ID')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                        ->weight(FontWeight::SemiBold)
                        ->icon('heroicon-s-shopping-bag')
                        ->color('primary')
                        ->searchable(),




                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('user.name')
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->icon('heroicon-s-user-circle')
                            ->color('gray')
                            ->sortable(),


                        Tables\Columns\TextColumn::make('plan.name')
                            ->alignRight()
                            ->badge()
                            ->sortable(),
                    ])->columnSpanFull()->extraAttributes(['class' => 'mb-2']),


                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('product.name')
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->icon('heroicon-c-ticket')
                            ->grow()
                            ->sortable(),

                        Tables\Columns\TextColumn::make('total')
                            ->money(Money::defaultCurrency())
                            ->weight(FontWeight::SemiBold)
                            ->alignRight()
                            ->sortable(),

                    ])->columnSpanFull()->extraAttributes(['class' => 'mb-2']),


                    Tables\Columns\Layout\Split::make([

                        Tables\Columns\TextColumn::make('quantity')
                            ->badge()
                            ->prefix('Qty:')
                            ->color('info')
                            ->sortable(),

                        Tables\Columns\TextColumn::make('status')
                            ->badge()
                            ->alignCenter()
                            ->searchable(),

                        Tables\Columns\IconColumn::make('payment_success')
                            ->label('isPaid')
                            ->icon('heroicon-c-stop')
                            ->alignRight()
                            ->boolean(),

//
//                        Tables\Columns\TextColumn::make('expire_at')
//                            ->dateTime()
//                            ->icon('heroicon-s-calendar-date-range')
//                            ->sortable(),

                    ])->columnSpanFull()->extraAttributes(['class' => 'mb-2']),


                ])->columnSpanFull(),




                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->icon('heroicon-s-calendar-date-range')
                        ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->dateTime()
                        ->alignRight()
                        ->sortable()
                        ->icon('heroicon-s-calendar-date-range')
                        ->toggleable(isToggledHiddenByDefault: true),
                ])->columnSpanFull(),

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


}
