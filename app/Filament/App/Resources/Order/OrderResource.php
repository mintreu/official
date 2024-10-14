<?php

namespace App\Filament\App\Resources\Order;

use App\Filament\App\Resources\Order\OrderResource\Pages;
use App\Filament\App\Resources\Order\OrderResource\RelationManagers;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
//    protected static ?string $navigationLabel = 'My Purchases';
//    protected static ?string $pluralLabel = 'My Purchases';
//    protected static ?string $slug = 'my-purchases';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('discount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tax')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('voucher'),
                Forms\Components\TextInput::make('tracking_id'),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\Toggle::make('payment_success')
                    ->required(),
                Forms\Components\DateTimePicker::make('expire_at')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->required()
                    ->options(User::all()->pluck('name','id')->toArray()),

                Forms\Components\Select::make('plan_id')
                    ->options(Plan::all()->pluck('name','id')->toArray()),

                Forms\Components\Select::make('product_id')
                    ->options(Product::where('status',true)->get()->pluck('name','id')->toArray())
                    ->required(),
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
