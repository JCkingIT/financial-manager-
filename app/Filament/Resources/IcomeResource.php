<?php

namespace App\Filament\Resources;

use App\Enums\TypeCategory;
use App\Filament\Resources\IcomeResource\Pages;
use App\Filament\Resources\IcomeResource\RelationManagers;
use App\Forms\Category;
use App\Models\Icome;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IcomeResource extends Resource
{
    protected static ?string $model = Icome::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Ingresos';
    protected static ?string $label = 'Ingresos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required()
                    ->dehydrated(),
                Forms\Components\Select::make('category_id')
                    ->label('Categoria')
                    ->placeholder('Selecciona una categoria')
                    ->relationship(
                        'category',
                        'name',
                        fn($query) => $query->where('type', TypeCategory::ICOME)
                    )
                    ->createOptionForm(Category::form(TypeCategory::ICOME))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Monto')
                    ->placeholder('Cantidad de dinero')
                    ->required()
                    ->numeric()
                    ->prefix('S/.'),
                Forms\Components\TextInput::make('fountain')
                    ->label('Fuente')
                    ->placeholder('Procedencia del dinero')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->placeholder('Detalle del ingreso')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function query(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->numeric()
                    ->sortable()
                    ->money('PEN'),
                Tables\Columns\TextColumn::make('fountain')
                    ->label('Fuente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de ingreso')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('Fecha editado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIcomes::route('/'),
            'create' => Pages\CreateIcome::route('/create'),
            'edit' => Pages\EditIcome::route('/{record}/edit'),
        ];
    }
}
