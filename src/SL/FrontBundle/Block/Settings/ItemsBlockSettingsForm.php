<?php

namespace SL\FrontBundle\Block\Settings;

use NS\AdminBundle\Form\Type\TinyMceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemsBlockSettingsForm extends AbstractType
{
	/**
	 * Builds form
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('limit', 'text', array(
                'label'    => 'Количество товаров',
                'required' => false,
            ))
        ;
    }

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SL\FrontBundle\Block\Settings\ItemsBlockSettingsModel'
        ));
    }

	/**
	 * @return string
	 */
	public function getName()
    {
        return 'sl_front_items_block';
    }
}
