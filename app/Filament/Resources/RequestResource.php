<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestResource\Pages;
use App\Filament\Resources\RequestResource\RelationManagers;
use App\Models\Request;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $navigationLabel = 'Solicitudes';
    protected static ?string $navigationIcon = 'heroicon-c-envelope-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Section::make("Descripcion de Solicitud")
               ->columns(1)
               ->schema([
                    Forms\Components\Textarea::make('Description')
                    ->label("Descripcion")
                    ->minLength(2)
                    ->maxLength(255)
                    ->required(),                                 
               ]),
               Section::make("Usuario y Estado Asignado")
               ->columns(2)
               ->schema([
                Forms\Components\Select::make('user_id')
                    ->label("Usuario")
                    ->relationship(name: "user", titleAttribute: "name")
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\Select::make("state_id")
                    ->label("Estado")
                    ->relationship(name: "state", titleAttribute: "type")
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
               ]),                  
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')                                 
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.type')
                    ->numeric()
                    ->sortable(),    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('state.type')
                ->options([
                    'pendiente' => 'pendientes',
                    'en proceso' => 'en procesos',
                    'finalizado' => 'finalizados',
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
        ];
    }
}
