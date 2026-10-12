<?php


namespace App\Application\Common;


class ArrayUtils
{

    public function arrayRecursiveDiff($aArray1, $aArray2): array
    {
        $aArray1 = $this->prepareArray($aArray1);
        $aArray2 = $this->prepareArray($aArray2);

        return $this->compare($aArray1, $aArray2);
    }

    private function compare($aArray1, $aArray2): array
    {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, (array) $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = $this->compare($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }

        return $aReturn;
    }

    private function prepareArray(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $childKey => $childValue) {
                    if (is_array($childValue)) {
                        if (is_string($childKey)) {
                            $result[$key][$childKey] = $childValue;
                        } else if (array_key_exists('id', $childValue)) {
                            $result[$key][$childValue['id']] = $this->prepareArray($childValue);
                        } else if (array_key_exists('uuid' , $childValue)) {
                            $result[$key][$childValue['uuid']] = $this->prepareArray($childValue);
                        } else {
                            $result[$key][$childKey] = $this->prepareArray($childValue);
                        }
                    } else {
                        $result[$key][$childKey] = $childValue;
                    }
                }
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function arrayContainsString(array $array, string $string): bool
    {
        foreach ($array as $element) {
            if (str_contains($element, $string)) {
                return true;
            }
        }

        return false;
    }
}
