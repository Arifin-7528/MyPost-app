<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535),
                Select::make('type')
                    ->options([
                        'posts' => 'Posts',
                        'users' => 'Users',
                        'comments' => 'Comments',
                        'activity' => 'Activity',
                    ])
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
