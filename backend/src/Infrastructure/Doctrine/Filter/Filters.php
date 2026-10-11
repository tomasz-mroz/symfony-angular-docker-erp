<?php


namespace App\Infrastructure\Doctrine\Filter;


use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Filters
{
    const OPERATOR_CONTAINS = 'contains';
    const OPERATOR_CONTAINS_FROM_LEFT = 'containsFromLeft';
    const OPERATOR_EQ = 'eq';
    const OPERATOR_NEQ = 'neq';
    const OPERATOR_IS_EMPTY = 'isEmpty';
    const OPERATOR_IS_NOT_EMPTY = 'isNotEmpty';
    const OPERATOR_IS_NULL = 'isNull';
    const OPERATOR_IS_NOT_NULL = 'isNotNull';
    const OPERATOR_LT = 'lt';
    const OPERATOR_LTE = 'lte';
    const OPERATOR_GT = 'gt';
    const OPERATOR_GTE = 'gte';
    const OPERATOR_IN = 'in';
    const OPERATOR_NOT_IN = 'notIn';

    private string $paramPrefix;
    private int $paramCount = 0;

    public function __construct(
        private array $filters,
        private array $types,
    )
    {
        $this->paramPrefix = Uuid::uuid4()->toString();
    }

    public function query(QueryBuilder $qb, string $alias): void
    {
        foreach ($this->filters as $field => $value) {
            if (str_contains($field, '$')) {
                $parts = explode('$', $field);

                if (count($parts) > 2) {
                    throw new BadRequestHttpException('Nieprawidłowy parametr query: ' . $field);
                }

                $operator = $parts[0];
                $fieldName = $parts[1];
            } else {
                $operator = self::OPERATOR_EQ;
                $fieldName = $field;
            }

            if (!isset($this->types[$fieldName])) {
                throw new BadRequestHttpException('Brak filtrowania dla pola: ' . $fieldName);
            }

            if ($this->types[$fieldName] === false) {
                continue;
            }

            $resultValue = $value;
            if ($this->types[$fieldName] === 'bool' and $resultValue !== null) {
                $resultValue = in_array(strtolower($resultValue), ['true', 1, '1', 'yes', 'tak'], true);
            }

            if ($resultValue !== null) {
                if ($this->types[$fieldName] === 'array') {
                    $resultValue = explode(',', $resultValue);
                } elseif ($this->types[$fieldName] === 'float') {
                    $resultValue = str_replace(',', '.', $resultValue);
                } else {
                    settype($resultValue, (string) $this->types[$fieldName]);
                }
            }

            $qb->andWhere($this->filter($qb, $alias.'.'.$fieldName, $resultValue, $operator));
        }
    }

    private function filter(QueryBuilder $qb, string $fieldName, mixed $fieldValue, string $operator)
    {
        $paramName = 'p'.md5($this->paramPrefix . $fieldName . $this->paramCount);
        $this->paramCount++;
        switch ($operator) {
            case self::OPERATOR_CONTAINS:
                $qb->setParameter($paramName, '%'.$fieldValue.'%');
                return $qb->expr()->like($fieldName, ':'.$paramName);
            case self::OPERATOR_CONTAINS_FROM_LEFT:
                $qb->setParameter($paramName, $fieldValue.'%');
                return $qb->expr()->like($fieldName, ':'.$paramName);
            case self::OPERATOR_EQ:
                $qb->setParameter($paramName, $fieldValue);
                return $qb->expr()->eq($fieldName, ':'.$paramName);
            case self::OPERATOR_NEQ:
                $qb->setParameter($paramName, $fieldValue);
                return $qb->expr()->neq($fieldName, ':'.$paramName);
            case self::OPERATOR_IS_EMPTY:
                $qb->setParameter('emptyParam', '');
                return $qb->expr()->orX(
                    $qb->expr()->isNull($fieldName),
                    $qb->expr()->eq($fieldName, ':emptyParam')
                );
            case self::OPERATOR_IS_NOT_EMPTY:
                $qb->setParameter('emptyParam', '');
                return $qb->expr()->not($qb->expr()->orX(
                    $qb->expr()->isNull($fieldName),
                    $qb->expr()->eq($fieldName, ':emptyParam')
                ));
            case self::OPERATOR_IS_NULL:
                return $qb->expr()->isNull($fieldName);
            case self::OPERATOR_IS_NOT_NULL:
                return $qb->expr()->isNotNull($fieldName);
            case self::OPERATOR_LT:
                if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $fieldValue)) {
                    $qb->setParameter($paramName, $fieldValue . ' 23:59:59');
                } else {
                    $qb->setParameter($paramName, $fieldValue);
                }

                return $qb->expr()->lt($fieldName, ':'.$paramName);
            case self::OPERATOR_LTE:
                if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $fieldValue)) {
                    $qb->setParameter($paramName, $fieldValue . ' 23:59:59');
                } else {
                    $qb->setParameter($paramName, $fieldValue);
                }

                return $qb->expr()->lte($fieldName, ':'.$paramName);
            case self::OPERATOR_GT:
                $qb->setParameter($paramName, $fieldValue);
                return $qb->expr()->gt($fieldName, ':'.$paramName);
            case self::OPERATOR_GTE:
                $qb->setParameter($paramName, $fieldValue);
                return $qb->expr()->gte($fieldName, ':'.$paramName);
            case self::OPERATOR_IN:
                $qb->setParameter($paramName, $fieldValue);
                return $qb->expr()->in($fieldName, ':'.$paramName);
            case self::OPERATOR_NOT_IN:
                $qb->setParameter($paramName, $fieldValue);
                return $qb->expr()->notIn($fieldName, ':'.$paramName);
            default:
                throw new BadRequestHttpException('Nieznany operator: ' . $operator);
        }
    }

    public function getValue(string $fieldName): ?string
    {
        return $this->filters[$fieldName] ?? null;
    }

    public function getBoolValue(string $fieldName): ?bool
    {
        $resultValue = $this->filters[$fieldName] ?? null;

        if (null === $resultValue) {
            return null;
        }

        return in_array(strtolower($resultValue), ['true', 1, '1', 'yes', 'tak'], true);
    }

    public function getAllvalue(): ?string
    {
        if (count($this->filters)>0)
        {
            $value = '';
            foreach ($this->filters as $fieldName => $fieldValue){
                $value = $value .'filter';
                $value = $value.'%5B'.$fieldName.'%5D'.'='.$fieldValue.'&';
            }
            return $value;

        }
        return  null;
    }

    public function setValue(string $fieldName, string $value): void
    {
        $this->filters[$fieldName] = $value;
    }
}
