<?php
namespace Rz\CkeditorBundle\Exception;

class DependencyInjectionException extends \Exception
{
    /**
     * Gets the "INVALID TOOLBAR ITEM" exception.
     *
     * @param string $item The invalid toolbar item.
     *
     * @return \Rz\Exception\DependencyInjectionException The "INVALID TOOLBAR ITEM" exception.
     */
    public static function invalidToolbarItem($item)
    {
        return new static(sprintf('The toolbar item "%s" does not exist.', $item));
    }

    /**
     * Getsthe "INVALID TOOLBAR" exception.
     *
     * @param string $toolbar The invalid toolbar.
     *
     * @return \Rz\Exception\DependencyInjectionException The "INVALID TOOLBAR" exception.
     */
    public static function invalidToolbar($toolbar)
    {
        return new static(sprintf('The toolbar "%s" does not exist.', $toolbar));
    }
}
