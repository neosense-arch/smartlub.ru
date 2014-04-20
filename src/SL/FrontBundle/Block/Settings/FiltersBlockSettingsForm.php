<?php

namespace SL\FrontBundle\Block\Settings;

use NS\AdminBundle\Form\Type\TinyMceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FiltersBlockSettingsForm extends AbstractType
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
            ->add('vendorCategoryId', 'category_select', array(
                'label'       => 'Категория каталога производителей',
                'required'    => false,
                'id_only'     => true,
                'empty_value' => '[ Не выбрано ]',
            ))
            ->add('priceFrom', 'text', array(
                'label'    => 'Минимальная стоимость',
                'required' => false,
            ))
            ->add('priceTo', 'text', array(
                'label'    => 'Максимальная стоимость',
                'required' => false,
            ))
            ->add('priceStep', 'text', array(
                'label'    => 'Шаг выбора стоимости',
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
            'data_class' => 'SL\FrontBundle\Block\Settings\FiltersBlockSettingsModel'
        ));
    }

	/**
	 * @return string
	 */
	public function getName()
    {
        return 'sl_front_filters_block';
    }
}
