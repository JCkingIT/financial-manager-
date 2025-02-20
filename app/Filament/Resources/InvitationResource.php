<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvitationResource\Pages;
use App\Filament\Resources\InvitationResource\RelationManagers;
use App\Models\Invitation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class InvitationResource extends Resource
{
    protected static ?string $model = Invitation::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'Invitaciones';
    protected static ?string $label = 'Invitaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->label('Correo')
                    ->email()
                    ->required()
                    ->unique('invitations', 'email')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('expiration')
                    ->label('Fecha de expiración')
                    ->required(),
                Forms\Components\Toggle::make('activated')
                    ->label('Activado')
                    ->default(false)
                    ->onColor('success')
                    ->offColor('danger')
                    ->required(),
                Forms\Components\Toggle::make('state')
                    ->label('Estado')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Codigo')
                    ->default(fn() => Str::uuid()->toString())
                    ->unique('invitations', 'code')
                    ->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Codigo')
                    ->searchable(),
                Tables\Columns\IconColumn::make('activated')
                    ->label('Activado')
                    ->boolean(),
                Tables\Columns\IconColumn::make('state')
                    ->label('Estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('expiration')
                    ->label('Fecha de expiracion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de registro')
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
            'index' => Pages\ListInvitations::route('/'),
            'create' => Pages\CreateInvitation::route('/create'),
            'edit' => Pages\EditInvitation::route('/{record}/edit'),
        ];
    }
}
