<?php

namespace App\Filament\Resources;

use App\Enums\BodiesTypeEnum;
use App\Filament\Resources\BodyResource\Pages;
use App\Filament\Resources\BodyResource\RelationManagers;
use App\Models\Body;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BodyResource extends Resource
{
    protected static ?string $model = Body::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->autofocus()->required()->label('Title'),
                Select::make('type')
                    ->label('Type')
                    ->options(BodiesTypeEnum::class)
                    ->required(),
                RichEditor::make('description')
                    ->fileAttachmentsDisk('public')
                    ->label('Description')
                    ->fileAttachmentsDirectory('bodies')
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
                    ->required(),
                FileUpload::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->directory('bodies')
                    ->required(),
                Select::make('galaxy_id')
                    ->relationship(name: 'galaxy', titleAttribute: 'title')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('galaxy.title')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('image_path')
                ->label('Image')
                ->extraImgAttributes(['title' => 'Body picture']),
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBodies::route('/'),
            'create' => Pages\CreateBody::route('/create'),
            'view' => Pages\ViewBody::route('/{record}'),
            'edit' => Pages\EditBody::route('/{record}/edit'),
        ];
    }
}
