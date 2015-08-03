<?php
namespace OPHP\Filter;

use OPHP\OString;
use OPHP\OStringFilter;

class FilterWithDefaults extends OStringFilter
{
    public function __construct(OString &$string, OString &$filtered)
    {
        foreach ($string as $letter) {
            if ((bool) $letter) {
                $filtered = $filtered->insert($letter);
            }
        }
    }
}
