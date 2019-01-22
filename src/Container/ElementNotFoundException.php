<?php
namespace Haystack\Container;

class ElementNotFoundException extends \Exception
{
    /**
     * ElementNotFoundException constructor.
     *
     * @param string          $element
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($element, $code = 0, \Exception $previous = null)
    {
        parent::__construct(
            sprintf('Element could not be found: %s', $element),
            $code,
            $previous
        );
    }
}
