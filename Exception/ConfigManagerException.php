<?php

namespace Rz\CkeditorBundle\Exception;

class ConfigManagerException extends \Exception
{
    /**
     * Gets the "CONFIG DOES NOT EXIST" exception.
     *
     * @param string $name The invalid CKEditor config name.
     *
     * @return \Rz\Exception\ConfigManagerException The "CONFIG DOES NOT EXIST" exception.
     */
    public static function configDoesNotExist($name)
    {
        return new static(sprintf('The CKEditor config "%s" does not exist.', $name));
    }
}
