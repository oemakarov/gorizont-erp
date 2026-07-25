<?php

namespace totum\common\Lang;

trait SearchTrait
{
    public function getSearchFunction($q): callable
    {

        $parsedQuery = new \stdClass();
        if (preg_match('/^([!\^~= ]+):\s*/', $q, $controlMatches)) {
            $q = substr($q, strlen($controlMatches[0]));
        }
        $q = $this->searchPrepare($q);
        $parsedQuery->searchVar = $q;
        $parsedQuery->searchArray = explode(' ', $q);

        if ($q === '') {
            return function ($v) {
                return true;
            };
        }


        $every = function ($array, $callback) {
            foreach ($array as $item) {
                if (!$callback($item)) {
                    return false;
                }
            }
            return true;
        };

        return match ($controlMatches[1] ?? '') {
            '!=' => function ($v) use ($parsedQuery) {
                $v = $this->searchPrepare($v);
                return $parsedQuery->searchVar !== $v;
            },
            '=' => function ($v) use ($parsedQuery) {
                $v = $this->searchPrepare($v);
                return $parsedQuery->searchVar === $v;
            },
            '~' => function ($v) use ($parsedQuery) {
                $v = $this->searchPrepare($v);
                return mb_strpos($v, $parsedQuery->searchVar) !== false;
            },
            '!~' => function ($v) use ($parsedQuery) {
                $v = $this->searchPrepare($v);
                return mb_strpos($v, $parsedQuery->searchVar) === false;
            },
            '!', '!~~' => function ($v) use ($every, $parsedQuery) {
                $v = $this->searchPrepare($v);
                return $every($parsedQuery->searchArray, function ($q) use ($v) {
                    return mb_strpos($v, $q) === false;
                });
            },
            '^' => function ($v) use ($every, $parsedQuery) {
                $v = $this->searchPrepare($v);
                $v = explode(' ', $v);
                return $every($parsedQuery->searchArray, function ($q) use ($v) {
                    foreach ($v as $_v) {
                        if (mb_strpos($_v, $q) === 0) {
                            return true;
                        }
                    }
                    return false;
                });
            },
            '!^' => function ($v) use ($every, $parsedQuery) {
                $v = $this->searchPrepare($v);
                $v = explode(' ', $v);
                return $every($parsedQuery->searchArray, function ($q) use ($v) {
                    foreach ($v as $_v) {
                        if (mb_strpos($_v, $q) === 0) {
                            return false;
                        }
                    }
                    return true;
                });
            },
            '^~' => function ($v) use ($parsedQuery) {
                $v = $this->searchPrepare($v);
                return mb_strpos($v, $parsedQuery->searchVar) === 0;
            },
            '!^~' => function ($v) use ($parsedQuery) {
                $v = $this->searchPrepare($v);
                return mb_strpos($v, $parsedQuery->searchVar) !== 0;
            },

            default => function ($v) use ($parsedQuery) {
                $v = $this->searchPrepare($v);
                foreach ($parsedQuery->searchArray as $q) {
                    if ($q !== '' && mb_stripos($v, $q) === false) {
                        return false;
                    }
                }
                return true;

            }
        };
    }


    public function searchPrepare($string): string
    {
        $search = ['ё', 'á', 'é', 'í', 'ó', 'ú', 'ü'];
        $replace = ['е', 'a', 'e', 'i', 'o', 'u', 'u'];

        return str_replace($search, $replace, mb_strtolower(trim((string)$string)));
    }

}