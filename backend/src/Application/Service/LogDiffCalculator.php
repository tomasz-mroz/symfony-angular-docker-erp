<?php


namespace App\Application\Service;


use App\Application\Cqrs\QueryResult\CalculatedLog;
use App\Application\Exception\EmptyLogException;
use App\Application\Exception\InvalidArgumentException;
use App\Entity\Log;
use App\Entity\LogToday;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Yaml\Yaml;

class LogDiffCalculator
{

    private array $securedValues = [
        'Hasło WS'
    ];

    private array $config;

    private bool $withSecured = false;

    public function __construct(
        #[Autowire('%kernel.project_dir%/config/data')]
        private readonly string $configDataDir,
    ) {
        $this->config = Yaml::parseFile($this->configDataDir . '/log.yaml');
    }

    /**
     * @param Log|LogToday[]$logs
     * @param string $entity
     * @return CalculatedLog[]
     */
    public function calculate(array $logs, string $entity, bool $withSecured): array
    {
        $config = $this->getConfig($entity);
        $this->withSecured = $withSecured;

        $result = [];

        foreach ($logs as $log) {
            $result[] = $this->calculateDiff($log, $config);
        }

        return $result;
    }

    private function getConfig(string $entity): array
    {
        foreach ($this->config['objects'] as $object) {
            if ($object['name'] === $entity) {
                return $object;
            }
        }

        throw new InvalidArgumentException();
    }

    private function calculateDiff(Log|LogToday $log, array $config): CalculatedLog
    {
        $result = [];

        foreach ($config['fields'] as $field) {
            try {
                switch ($field['type']) {
                    case 'string':
                        $values = $this->getValues($log->getPrevious(), $log->getChanges(), $field['path']);
                        $values['displayName'] = $field['displayName'];
                        $result[] = $values;
                        break;
                    case 'double':
                        $values = $this->getValues($log->getPrevious(), $log->getChanges(), $field['path']);
                        $values['displayName'] = $field['displayName'];
                        $values['previous'] = $values['previous'] === null ? '' : number_format($values['previous'], 2, '.', '');
                        $values['new'] = $values['new'] === null ? '' : number_format($values['new'], 2, '.', '');
                        $result[] = $values;
                        break;
                    case 'bool':
                        $values = $this->getValues($log->getPrevious(), $log->getChanges(), $field['path'], boolValues: true);
                        $values['displayName'] = $field['displayName'];
                        $result[] = $values;
                        break;
                    case 'date':
                        $values = $this->getValues($log->getPrevious(), $log->getChanges(), $field['path'], dateValues: true);
                        $values['displayName'] = $field['displayName'];
                        $result[] = $values;
                        break;
                    case 'datetime':
                        $values = $this->getValues($log->getPrevious(), $log->getChanges(), $field['path'], dateTimeValues: true);
                        $values['displayName'] = $field['displayName'];
                        $result[] = $values;
                        break;
                    case 'array':
                        $previousArray = array_values((array) $this->getNestedVar($log->getPrevious(), $field['path']));
                        $newArray = array_values((array) $this->getNestedVar($log->getChanges(), $field['path']));
                        $childConfig = $this->getConfig($field['itemType']);

                        $n = 0;
                        $records = [];

                        while (isset($previousArray[$n]) or isset($newArray[$n])) {
                            foreach ($childConfig['fields'] as $childField) {
                                try {
                                    switch ($childField['type']) {
                                        case 'string':
                                            $values = $this->getValues($previousArray[$n] ?? [], $newArray[$n] ?? [], $childField['path']);
                                            $values['displayName'] = $childField['displayName'];
                                            $records[] = $values;
                                            break;
                                        case 'double':
                                            $values = $this->getValues($previousArray[$n] ?? [], $newArray[$n] ?? [], $childField['path']);
                                            $values['displayName'] = $childField['displayName'];
                                            $values['previous'] = $values['previous'] === null ? '' : number_format($values['previous'], 2, '.', '');
                                            $values['new'] = $values['new'] === null ? '' : number_format($values['new'], 2, '.', '');
                                            $records[] = $values;
                                            break;
                                        case 'bool':
                                            $values = $this->getValues($previousArray[$n] ?? [], $newArray[$n] ?? [], $childField['path'], boolValues: true);
                                            $values['displayName'] = $childField['displayName'];
                                            $records[] = $values;
                                            break;
                                        case 'date':
                                            $values = $this->getValues($previousArray[$n] ?? [], $newArray[$n] ?? [], $childField['path'], dateValues: true);
                                            $values['displayName'] = $childField['displayName'];
                                            $records[] = $values;
                                            break;
                                        case 'datetime':
                                            $values = $this->getValues($previousArray[$n] ?? [], $newArray[$n] ?? [], $childField['path'], dateTimeValues: true);
                                            $values['displayName'] = $childField['displayName'];
                                            $records[] = $values;
                                            break;
                                    }
                                } catch (EmptyLogException){}
                            }

                            $n++;
                        }

                        if (count($records) > 0) {
                            $result[] = [
                                'displayName' => $field['displayName'],
                                'records' => $records
                            ];
                        }

                        break;
                    default:
                        $childConfig = $this->getConfig($field['type']);
                        $previousArray = (array) $this->getNestedVar($log->getPrevious(), $field['path']);
                        $newArray = (array) $this->getNestedVar($log->getChanges(), $field['path']);

                        foreach ($childConfig['fields'] as $childField) {
                            try {
                                $values = $this->getValues($previousArray, $newArray, $childField['path'], $childField['type'] === 'bool');
                                $values['displayName'] = $field['displayName'] . ' > ' . $childField['displayName'];
                                $result[] = $values;
                            } catch (EmptyLogException){}

                        }
                }
            } catch (EmptyLogException){}
        }

        return new CalculatedLog($log, $result);
    }

    private function getValues(array $previous, array $changes, string $path, bool $boolValues = false, bool $dateValues = false, bool $dateTimeValues = false): array
    {
        $previousVal = $this->getNestedVar($previous, $path);
        $changedVal = $this->getNestedVar($changes, $path);

        if ($previousVal === null and $changedVal === null) {
            throw new EmptyLogException();
        }

        if ($boolValues) {
            if ($previousVal === null) {
                throw new EmptyLogException();
            }

            $previousVal = $previousVal ? 'Tak' : 'Nie';
            $changedVal = $changedVal ? 'Tak' : 'Nie';
        }

        if ($dateValues) {
            $previousVal = $previousVal ? date('Y-m-d', strtotime($previousVal)) : '';
            $changedVal = $changedVal ? date('Y-m-d', strtotime($changedVal)) : '';
        }

        if ($dateTimeValues) {
            date_default_timezone_set('Europe/Warsaw');
            $previousVal = $previousVal ? date('Y-m-d H:i', strtotime($previousVal)) : '';
            $changedVal = $changedVal ? date('Y-m-d H:i', strtotime($changedVal)) : '';
        }

        if (!$this->withSecured) {
            foreach ($this->securedValues as $securedValue) {
                if (str_contains($previousVal, $securedValue) or str_contains($changedVal, $securedValue)) {
                    throw new EmptyLogException();
                }
            }
        }

        return [
            'previous' => $previousVal,
            'new' => $changedVal,
        ];
    }

    private function getNestedVar($context, $name) {
        $pieces = explode('.', $name);
        foreach ($pieces as $piece) {
            if (!is_array($context) || !array_key_exists($piece, $context)) {
                return null;
            }
            $context = &$context[$piece];
        }
        return $context;
    }
}
