<?php

namespace App\Filament\Resources;

use App\Enums\BodiesTypeEnum;
use App\Filament\Resources\BodyResource\Pages;
use App\Filament\Resources\BodyResource\RelationManagers;
use App\Models\Body;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                MarkdownEditor::make('description')
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
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListBodies::route('/'),
            'create' => Pages\CreateBody::route('/create'),
            'edit' => Pages\EditBody::route('/{record}/edit'),
        ];
    }
}
