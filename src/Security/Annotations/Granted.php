<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Security\Annotations;

/**
 * Class Granted
 *
 * @Annotation
 *
 * @package App\Base\Security
 */
class Granted
{
    /**
     * @var array
     */
    private $validators = [];

    /**
     * Granted constructor.
     * @param array $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->validators;
    }
}