<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Selecione un usuario")
               ->columns(3)
               ->schema([
                    Forms\Components\Hidden::make('name')
                        ->reactive()                      
                        ->required(),
                    Forms\Components\Hidden::make('url_address')
                        ->reactive()                     
                        ->required(),
                    Forms\Components\Select::make('user_id')
                        ->relationship(name: "user", titleAttribute: "name")
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('type_id')
                        ->relationship(name: "type", titleAttribute: "type")
                        ->preload()
                        ->required(),                                                                     
               ]),
                 
                Section::make("Subida de Archivo")
               ->columns(1)
               ->schema([
                    Forms\Components\FileUpload::make("file")
                    ->label("Subir Archivo")
                    ->directory("uploads")
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            // Obtiene la ruta completa donde se ha guardado el archivo en el disco 'public'
                            $urlFile = "storage/uploads/".basename($state); //Storage::disk('public')->path($state);
                            // Obtén la información del archivo
                            $nameFile = $state->getClientOriginalName(); //pathinfo($urlFile, PATHINFO_BASENAME);  // Nombre del archivo con extensión
                            //dd($urlFile);
                            // Guarda la ruta en otro campo (ej. campo_1) o en la base de datos
                            $set('url_address', $urlFile);
                            $set('name', $nameFile);
                        }
                    })
                    ->required(),                                 
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url_address')
                    ->hidden(true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type.type')
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
