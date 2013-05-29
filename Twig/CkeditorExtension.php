<?php

namespace Rz\CkeditorBundle\Twig;

use Rz\CkeditorBundle\Model\ConfigManagerInterface;
use Rz\CkeditorBundle\Exception\ConfigManagerException;

class CkeditorExtension extends \Twig_Extension
{

    protected $configManager;

    public function __construct(ConfigManagerInterface $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     *
     * @access public
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'rz_ckeditor_config' => new \Twig_Function_Method($this, 'getCKEditorConfig', array('is_safe' => array('html'))),
        );
    }

    /**
     *
     * Truncates string if longer than needed and appends '...' to signify shorthand, otherwise returns original string.
     *
     * @access public
     *
     * @param $configIndex
     * @param string $toolbarType
     *
     * @throws \Rz\Exception\ConfigManagerException
     * @internal param $needle
     * @internal param $haystack
     *
     * @return int
     */
    public function getCKEditorConfig($toolbarType = 'inline_editor', $configIndex = null)
    {
        $config = $this->configManager->getConfig($toolbarType);
        if ($configIndex) {
            if (!array_key_exists($configIndex, $config)) {
                throw ConfigManagerException::configDoesNotExist($configIndex);
            }
            return $config[$configIndex];
        } else {
            return $config;
        }
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'rz_ckeditor';
    }

}
