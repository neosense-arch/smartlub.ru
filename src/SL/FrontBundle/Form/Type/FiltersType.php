<?php

namespace SL\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FiltersType
 *
 * @package SL\FrontBundle\Form\Type
 */
class FiltersType extends AbstractType
{
    /**
     * @var int
     */
    private $categoryId;

    /**
     * @param $categoryId
     */
    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vendor', 'ns_catalog_node_select', array(
                'categoryId'  => $this->categoryId,
                'required'    => false,
                'empty_value' => 'Выберите производителя'
            ))
            ->add('priceFrom', 'text', array(
                'required' => false,
            ))
            ->add('priceTo', 'text', array(
                'required' => false,
            ))
        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'sl_front_filters';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SL\FrontBundle\Model\Filters',
        ));
    }
}