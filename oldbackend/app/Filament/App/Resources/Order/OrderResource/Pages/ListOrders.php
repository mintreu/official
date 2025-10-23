<?php

namespace App\Filament\App\Resources\Order\OrderResource\Pages;

use App\Filament\App\Resources\Order\OrderResource;
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
            Actions\CreateAction::make()->label('Create Project'),
        ];
    }







    public  function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('user_id','=',filament()->auth()->user()->id))
            ->contentGrid([
                'md' => 2,
                'lg' => 3,
            ])
            ->columns([

                Tables\Columns\Layout\Stack::make([

                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('uuid')
                            ->label('Order Id')
                            ->weight(FontWeight::SemiBold),

                        Tables\Columns\TextColumn::make('status')
                            ->badge()
                            ->alignRight()
                            ->searchable(),
                    ]),

                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('plan.name')
                            ->badge()
                            ->prefix(__('Plan : '))
                            ->sortable(),
                        Tables\Columns\TextColumn::make('product.name')
                            ->badge()
                            ->prefix(__('Product : '))
                            ->sortable(),
                    ]),

                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('expire_at')
//                            ->dateTime()
                            ->since()
                            ->prefix('Expire At : ')
                            ->grow()
                            ->sortable(),

                        Tables\Columns\IconColumn::make('payment_success')
                            ->alignRight()
                            ->columnSpanFull()
                            ->boolean(),
                    ]),



//                    Tables\Columns\Layout\Split::make([
//
//                        Tables\Columns\TextColumn::make('subtotal')
//                            ->money(Money::defaultCurrency())
//                            ->description('Subtotal','after')
//                            ->sortable(),
//
//                        Tables\Columns\TextColumn::make('discount')
//                            ->money(Money::defaultCurrency())
//                            ->description('Discount','after')
//                            ->sortable(),
//                        Tables\Columns\TextColumn::make('tax')
//                            ->money(Money::defaultCurrency())
//                            ->description('Tax','after')
//                            ->sortable(),
//
//                    ]),
//
//                    Tables\Columns\Layout\Split::make([
//                        Tables\Columns\TextColumn::make('amount')
//                            ->money(Money::defaultCurrency())
//                            ->description('Amount','after')
//                            ->sortable(),
//
//                        Tables\Columns\TextColumn::make('total')
//                            ->money(Money::defaultCurrency())
//                            ->description('Total','after')
//                            ->sortable(),
//
//                    ]),

                    Tables\Columns\Layout\Split::make([

//                        Tables\Columns\TextColumn::make('voucher')
//                            ->description('Voucher','after')
//                            ->searchable(),
                    ]),




//                    Tables\Columns\TextColumn::make('tracking_id')
//                        ->description('Tracking ID','after')
//                        ->searchable(),



//                    Tables\Columns\TextColumn::make('user_id')
//                        ->numeric()
//                        ->sortable(),



                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('created_at')
                            ->dateTime()
                            ->sortable()
                            ->description('Created','after')
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('updated_at')
                            ->dateTime()
                            ->sortable()
                            ->description('Updated','after')
                            ->toggleable(isToggledHiddenByDefault: true),
                    ]),



                ]),





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
