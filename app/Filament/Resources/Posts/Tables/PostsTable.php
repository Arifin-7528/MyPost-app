<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ViewColumn;


class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->withCount('likes') 
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                ViewColumn::make('file_path')
                    ->label('Posts')
                    ->view('filament.tables.columns.media-preview'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->counts('likes')
                    ->sortable(),
                TextColumn::make('comments_count')
                    ->label('Comments')
                    ->counts('comments')
                    ->sortable(),
                TextColumn::make('caption')
                    ->label('Caption')
                    ->limit(50)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                // Filter::make('popular_posts')
                //     ->label('50+')
                //     ->query(fn(Builder $query): Builder => $query->has('likes', '>=', 50)),
                Filter::make('type')
                    ->form([
                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'image' => 'Image',
                                'video' => 'Video',
                            ])
                            ->placeholder('Select type'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['type']) && $data['type'] !== '') {
                            $query->where('type', $data['type']);
                        }
                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['type'] ?? null) {
                            $indicators['type'] = 'Type: ' . ucfirst($data['type']);
                        }
                        return $indicators;
                    }),
                Filter::make('likes_count')
                    ->form([
                        TextInput::make('likes_count')
                            ->label('Likes filter')
                            ->numeric()
                            ->placeholder('Enter likes'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['likes_count']) && $data['likes_count'] !== '') {
                            $query->whereRaw('(select count(*) from likes where likes.post_id = posts.id) = ?', [(int) $data['likes_count']]);
                        }
                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['likes_count'] ?? null) {
                            $indicators['likes_count'] = 'Likes: ' . $data['likes_count'];
                        }
                        return $indicators;
                    }),
            ])
            ->searchable()
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->recordUrl(null)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
