<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\ManageContentResource\Pages;
use App\Models\Content;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route; // Import Route facade
use Filament\Tables\Actions\Action;

class ManageContentResource extends Resource
{
    protected static ?string $model = Content::class;
    // protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Content Management';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('satuan')->required(),
                Forms\Components\Select::make('pilar')
                    ->options([
                        'News' => 'News',
                        'Opini' => 'Opini',
                        'Artikel' => 'Artikel',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('judul')->required(),
                Forms\Components\Textarea::make('deskripsi')->required(),
                FileUpload::make('media')
                    ->multiple()
                    ->directory('uploads/media')
                    ->maxSize(10240)
                    ->acceptedFileTypes(['image/*', 'video/mp4', 'application/pdf'])
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('satuan'),
                Tables\Columns\TextColumn::make('pilar'),
                Tables\Columns\TextColumn::make('judul'),
                Tables\Columns\TextColumn::make('deskripsi'),
            ])
            ->filters([
                
            ])
            ->actions([
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Content $record) => $record->delete()),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),
                    
            ]);
           
    }
    
  

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManageContents::route('/'),
            'create' => Pages\CreateManageContent::route('/create'),
            'edit' => Pages\EditManageContent::route('/{record}/edit'),
        ];
    }
}
