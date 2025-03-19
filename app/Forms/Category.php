<?php

namespace App\Forms;

use App\Enums\TypeCategory;
use Filament\Forms\Form;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;

class Category
{
    public static function form($typeCategory = ''): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->autocapitalize('words')
                ->required()
                ->unique('categories', 'name'),
            Forms\Components\Grid::make(['sm' => 2])
                ->schema([
                    empty($typeCategory) ? self::typeSelect() : self::typeInput($typeCategory),
                    Forms\Components\Select::make('icon')
                        ->label('Ícono Representativo')
                        ->options([
                            'currency-dollar' => 'Dinero 💰',
                            'arrow-up-circle' => 'Ingreso ⬆️',
                            'banknotes' => 'Depósito 🏦',
                            'chart-bar' => 'Gráfica 📈',
                            'plus-circle' => 'Suma ➕',
                        ])
                        ->searchable()
                ]),
            Forms\Components\Hidden::make('user_id')
                ->default(Auth::id())
                ->required()
                ->dehydrated(),
            Forms\Components\TextArea::make('description')
                ->label('Descripción')
                ->required(),
        ];
    }

    public static function typeInput($typeCategory): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('type')
            ->label('Tipo')
            ->default($typeCategory)
            ->required()
            ->readOnly();
    }

    public static function typeSelect(): Forms\Components\Select
    {
        return Forms\Components\Select::make('type')
            ->label('Tipo')
            ->options(TypeCategory::options())
            ->required();
    }
}
