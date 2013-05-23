<?php

/*
 * This file is part of the RzCkeditorBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\CkeditorBundle\Form\Type;

use Rz\CkeditorBundle\Model\ConfigManagerInterface,
    Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\FormView,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CkeditorType extends AbstractType
{
    /** @var \Rz\CkeditorBundle\Model\ConfigManager */
    protected $configManager;
    protected $router;

    /**
     * Creates a CKEditor type.
     *
     * @param \Rz\CkeditorBundle\Model\ConfigManagerInterface $configManager The CKEditor config manager.
     */
    public function __construct(ConfigManagerInterface $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $config = $options['config'] ? $options['config'] : null;

        if ($options['config_name'] !== null) {
            if ($config !== null) {
                $config = array_merge($this->configManager->getConfig($options['config_name']), $config);
            } else {
                $config = $this->configManager->getConfig($options['config_name']);
            }
        }

        $view->vars = array_replace($view->vars, array(
            'config'  => $config,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'required'   => false,
            'config_name'   => 'simple_editor',
            'config' => null,
        ));

        $resolver->addAllowedValues(array('required' => array(false),
                                          'config_name'=>array('admin_full_editor',
                                                               'simple_editor',
                                                               'inline_editor',
                                                               'bbcode_editor',
                                                               'minimal_editor')
                                          )
                                    );
    }

    public function setRouter(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rz_ckeditor';
    }

}
