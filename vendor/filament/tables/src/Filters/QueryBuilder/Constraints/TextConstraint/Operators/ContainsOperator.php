<?php

namespace Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint\Operators;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables\Filters\QueryBuilder\Constraints\Operators\Operator;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Str;

class ContainsOperator extends Operator
{
    public function getName(): string
    {
        return 'contains';
    }

    public function getLabel(): string
    {
        return __(
            $this->isInverse() ?
                'filament-tables::filters/query-builder.operators.text.contains.label.inverse' :
                'filament-tables::filters/query-builder.operators.text.contains.label.direct',
        );
    }

    public function getSummary(): string
    {
        return __(
            $this->isInverse() ?
                'filament-tables::filters/query-builder.operators.text.contains.summary.inverse' :
                'filament-tables::filters/query-builder.operators.text.contains.summary.direct',
            [
                'attribute' => $this->getConstraint()->getAttributeLabel(),
                'text' => $this->getSettings()['text'],
            ],
        );
    }

    /**
     * @return array<Component | Action | ActionGroup>
     */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('text')
                ->label(__('filament-tables::filters/query-builder.operators.text.form.text.label'))
                ->required()
                ->columnSpanFull(),
        ];
    }

    public function apply(Builder $query, string $qualifiedColumn): Builder
    {
        $text = trim($this->getSettings()['text']);

        /** @var Connection $databaseConnection */
        $databaseConnection = $query->getConnection();

        $isPostgres = $databaseConnection->getDriverName() === 'pgsql';

        if ($isPostgres) {
            $parts = explode('.', $qualifiedColumn);

            if (count($parts) === 3) {
                [$schema, $table, $column] = $parts;
                $table = "{$schema}.{$table}";
            } else {
                [$table, $column] = $parts;
            }

            if (Str::lower($table) !== $table) {
                $table = collect(explode('.', $table))
                    ->map(fn (string $segment): string => "\"{$segment}\"")
                    ->implode('.');
            }

            if (Str::lower($column) !== $column) {
                $column = "\"{$column}\"";
            }

            $qualifiedColumn = new Expression("lower({$table}.{$column}::text)");
            $text = Str::lower($text);
        }

        return $query->{$this->isInverse() ? 'whereNot' : 'where'}($qualifiedColumn, 'like', "%{$text}%");
    }
}
