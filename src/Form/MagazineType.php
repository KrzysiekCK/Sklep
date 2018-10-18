<?php

namespace App\Form;

use App\Entity\Color;
use App\Entity\Magazine;
use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MagazineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, array(
                'label' => 'Produkt',
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('product')
                        ->orderBy('product.name', 'ASC');
                },
                'choice_label' => 'name',
                'choice_value' => function (Product $entity = null) {
                    return $entity ? $entity->getId() : '';
                },
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('image', FileType::class, array(
                'label' => 'Zdjęcie'
            ))
            ->add('color', EntityType::class, array(
                'label' => 'Kolor',
                'class' => Color::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('color')
                        ->orderBy('color.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('new', CheckboxType::class, array(
                'label' => 'Nowość',
                'label_attr' => array('class' => 'checkbox-custom')
            ))
            ->add('sale', NumberType::class, array(
                'label' => 'Wyprzedaż (%)',
                'attr' => array('min' => 0, 'max' => 100)
            ))
            ->add('unisize', NumberType::class, array(
                'label' => 'Unisize',
                'data' => 12,
            ))
            ->add('xs', NumberType::class, array(
                'label' => 'XS'
            ))
            ->add('s', NumberType::class, array(
                'label' => 'S'
            ))
            ->add('m', NumberType::class, array(
                'label' => 'M'
            ))
            ->add('l', NumberType::class, array(
                'label' => 'L'
            ))
            ->add('xl', NumberType::class, array(
                'label' => 'XL'
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Magazine::class,
        ));
    }
}
