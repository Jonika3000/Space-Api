<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalaxyResource\Pages;
use App\Filament\Resources\GalaxyResource\RelationManagers;
use App\Models\Galaxy;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GalaxyResource extends Resource
{
    protected static ?string $model = Galaxy::class;
    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->autofocus()->required(),
                MarkdownEditor::make('description')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('galaxy')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'heading',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'table',
                        'undo',
                    ])
                    ->placeholder('description')
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Created')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListGalaxies::route('/'),
            'create' => Pages\CreateGalaxy::route('/create'),
            'edit' => Pages\EditGalaxy::route('/{record}/edit'),
        ];
    }
}
