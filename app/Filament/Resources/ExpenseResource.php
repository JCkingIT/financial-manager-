<?php

namespace App\Filament\Resources;

use App\Enums\TypeCategory;
use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Filament\Resources\ExpenseResource\Widgets\ExpenseStats;
use App\Filament\Widgets\Chart\ExpenseChart;
use App\Forms\Category;
use App\Models\Expense;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationLabel = 'Gastos';
    protected static ?string $label = 'Gastos';

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
                    ->placeholder('Seleccionar Categoria')
                    ->relationship(
                        'category',
                        'name',
                        fn($query) => $query->where('type', TypeCategory::EXPENSE)
                    )
                    ->createOptionForm(Category::form(TypeCategory::EXPENSE))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Monnto')
                    ->placeholder('0')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->prefix('S/.')
                    ->required()
                    ->disabled(fn(Forms\Get $get) => !empty($get('items')))
                    ->dehydrated(),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->placeholder('Detalle del gasto')
                    ->columnSpanFull(),
                Forms\Components\Section::make('Sub gastos')
                    ->headerActions([
                        Action::make('Reiniciar')
                            ->modalHeading('¿Estas seguro?')
                            ->modalDescription('Todos lo sub gastos se eliminaran.')
                            ->requiresConfirmation()
                            ->color('danger')
                            ->action(fn(Forms\Set $set) => $set('items', [])),
                    ])
                    ->schema([
                        static::getItemsRepeater(),
                    ])
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
                    ->money('S/.'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de gasto')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha editado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship(
                        'category',
                        'name',
                        fn($query) => $query->where('type', TypeCategory::EXPENSE)
                    )
                    ->label('Categoria')
                    ->preload()
                    ->default(null),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Gastos desde'),
                        Forms\Components\DatePicker::make('created_to')
                            ->label('Gastos hasta'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn($query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_to'],
                                fn($query, $date) => $query->whereDate('created_at', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data) {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Gastos desde ' . Carbon::parse($data['created_from'])->toFormattedDateString());
                        }
                        if ($data['created_to'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Gastos hasta ' . Carbon::parse($data['created_to'])->toFormattedDateString());
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(fn($record) => $record->items->count() <= 2)
                    ->url(fn($record) => $record->items->count() > 2
                        ? static::getUrl('view', ['record' => $record])
                        : null),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Components\Section::make()
                ->schema([
                    Components\Grid::make(['default' => 2])
                        ->schema([
                            Components\TextEntry::make('category.name')
                                ->label('Categoria'),
                            Components\TextEntry::make('amount')
                                ->label('Monto')
                                ->money('S/.'),
                            Components\TextEntry::make('created_at')
                                ->label('Fecha de gasto')
                                ->dateTime('d/m/Y'),
                            Components\TextEntry::make('updated_at')
                                ->label('Fecha editado')
                                ->dateTime('d/m/Y'),
                        ]),
                    Components\TextEntry::make('description')
                        ->label('Descripción'),
                ]),
            Components\Section::make('Sub gastos')
                ->icon('heroicon-o-queue-list')
                ->hidden(fn($record) => $record->items->isEmpty())
                ->schema([
                    Components\RepeatableEntry::make('items')
                        ->label('')
                        ->schema([
                            Components\TextEntry::make('description')
                                ->label(''),
                            Components\TextEntry::make('cost')
                                ->label('')
                                ->money('S/.'),
                        ])
                        ->columns(['default' => 2])
                        ->grid(2)
                ])
                ->collapsible()
                ->persistCollapsed()
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ExpenseStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'view' => Pages\ViewExpense::route('/{record}'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }

    public static function getItemsRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('items')
            ->relationship()
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Descripción del item')
                            ->autocapitalize('words')
                            ->live(onBlur: true)
                            ->required(),
                        Forms\Components\TextInput::make('cost')
                            ->label('Costo')
                            ->placeholder('Costo del item')
                            ->live(onBlur: true)
                            ->numeric()
                            ->prefix('S/.')
                            ->required(),
                    ]),
            ])
            ->collapsed()
            ->grid(2)
            ->afterStateUpdated(fn($state, callable $set) => $set('amount', collect($state)->sum('cost')))
            ->addable()
            ->defaultItems(0)
            ->hiddenLabel()
            ->itemLabel(
                function (array $state): ?string {
                    $label = $state['description'] ?? null;
                    $label = $state['cost'] ? $label . ' S/. ' . $state['cost'] : $label;

                    return $label;
                }
            )
            ->deletable();
    }
}
