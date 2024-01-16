<?php

namespace App\EloquentBuilders;

use Illuminate\Database\Eloquent\Builder;

class FilterBuilder extends Builder
{
    public const WHERE = 'where';

    public const WHERE_OR = 'orWhere';

    public const OPERATOR_EQUAL = '=';

    public const OPERATOR_NOT_EQUAL = '!=';

    public const OPERATOR_LESS_THAN = '<';

    public const OPERATOR_LESS_THAN_OR_EQUAL = '<=';

    public const OPERATOR_GREATER_THAN = '>';

    public const OPERATOR_GREATER_THAN_OR_EQUAL = '>=';

    public const OPERATOR_LIKE = 'like';

    public const OPERATOR_START_WITH = 'startWith';

    public const OPERATOR_END_WITH = 'endWith';

    public const OPERATOR_CONTAINS = 'contain';

    public const OPERATOR_IN = 'in';

    public const OPERATOR_NOT_IN = 'notIn';

    public const RELATION_FIELD_SEPARATOR = '.';

    public function buildFilter(array $queries): self
    {
        foreach ($queries as $query) {
            [$whereType, $field, $operator, $value] = $query;

            $this->when(! empty($value), function ($q) use ($whereType, $field, $operator, $value) {
                if ($this->isRelationField($field)) {
                    $this->buildWhereHasClause($q, $whereType, $field, $operator, $value);
                } elseif (in_array($operator, [self::OPERATOR_IN, self::OPERATOR_NOT_IN])) {
                    $q->whereIn(
                        $field,
                        $this->parseQueryValue($operator, $value),
                        $whereType === self::WHERE_OR ? 'or' : 'and',
                        $operator === self::OPERATOR_NOT_IN
                    );
                } else {
                    $q->$whereType($field, $this->parseQueryOperator($operator), $this->parseQueryValue($operator, $value));
                }
            });
        }

        return $this;
    }

    private function buildWhereHasClause(Builder $query, string $whereType, string $field, string $operator, string|array $value): void
    {
        $parseFields = explode(self::RELATION_FIELD_SEPARATOR, $field);

        if (count($parseFields) == 1) {
            return;
        }

        $relationField = array_shift($parseFields);
        $whereHasType = $whereType === self::WHERE_OR ? 'orWhereHas' : 'whereHas';
        $query->$whereHasType($relationField, function ($q) use (
            $parseFields,
            $operator,
            $value
        ) {
            if (count($parseFields) > 1) {
                $this->buildWhereHasClause($q, self::WHERE, implode(self::RELATION_FIELD_SEPARATOR, $parseFields), $operator, $value);
            } else {
                if (in_array($operator, [self::OPERATOR_IN, self::OPERATOR_NOT_IN])) {
                    $q->whereIn(
                        $parseFields[0],
                        $this->parseQueryValue($operator, $value),
                        'and',
                        $operator === self::OPERATOR_NOT_IN
                    );
                } else {
                    $q->where($parseFields[0], $this->parseQueryOperator($operator), $this->parseQueryValue($operator, $value));
                }
            }
        });
    }

    private function isRelationField(string $field): bool
    {
        return str_contains($field, self::RELATION_FIELD_SEPARATOR);
    }

    private function parseQueryOperator(string $operator): string
    {
        if (in_array($operator, [self::OPERATOR_START_WITH, self::OPERATOR_END_WITH, self::OPERATOR_CONTAINS])) {
            return self::OPERATOR_LIKE;
        }

        return $operator;
    }

    private function parseQueryValue(string $operator, string|array $value): string|array
    {
        return match ($operator) {
            self::OPERATOR_START_WITH => '%'.$value,
            self::OPERATOR_END_WITH => $value.'%',
            self::OPERATOR_CONTAINS => '%'.$value.'%',
            default => $value,
        };
    }
}
