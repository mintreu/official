<?php

namespace App\Filament\Common\Resources\Studio\StudioResource\Pages;

use App\Filament\Common\Resources\Studio\StudioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Model;

class ListStudios extends ListRecords
{
    protected static string $resource = StudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-m-plus-circle'),
        ];
    }



    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $user = filament()->auth()->user();
                $panelId = filament()->getCurrentPanel()->getId();

                // If we're in the 'app' panel, restrict to studios hosted by this user
                if ($panelId === 'app') {
                    $query->where('host_type', get_class($user))
                        ->where('host_id', $user->id);
                }

                return $query;
            })
            ->contentGrid([
                'md' => 2,
            ])
            ->columns([

                Tables\Columns\Layout\Stack::make([

                    Split::make([
                        Tables\Columns\TextColumn::make('name')
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->weight(FontWeight::SemiBold)
                            ->icon('heroicon-c-bookmark-square')
                            ->color('primary')
                            ->searchable(),

                        Tables\Columns\TextColumn::make('plan.name')
                            ->badge()
                            ->alignRight()
                            ->sortable(),
                    ])->columnSpanFull()->extraAttributes(['class' => 'mb-2']),

                    Split::make([
                        Tables\Columns\TextColumn::make('domain')
                            ->icon('heroicon-s-globe-alt')
                            ->getStateUsing(fn(Model $record) => $record->domain_schema.$record->domain)
                            ->searchable(),

                        Tables\Columns\TextColumn::make('product.name')
                            ->badge()
                            ->alignRight()
                            ->sortable(),
                    ])->columnSpanFull()->extraAttributes(['class' => 'mb-2']),

                    Tables\Columns\TextColumn::make('expire_on')
                        ->dateTime()
                        ->description('Expire On')
                        ->sortable(),


                ])->columnSpanFull(),



                Split::make([
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->icon('heroicon-s-calendar-date-range')
                        ->description('Created')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->dateTime()
                        ->icon('heroicon-s-calendar-date-range')
                        ->description('Last Update')
                        ->sortable()
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
