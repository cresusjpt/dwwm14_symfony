<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('images', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image(),
                ],
                'attr' => [
                    'multiple' => true
                ]
            ])
            ->add('name', null, [
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('description', null, [
                'attr' => [
                    'placeholder' => 'Description'
                ]
            ])
            ->add('size', null, [
                'attr' => [
                    'placeholder' => 'Taille'
                ]
            ])
            ->add('color', ColorType::class)

            ->add('price', null, [
                'attr' => [
                    'placeholder' => 'Prix  '
                ]
            ])
            ->add('qte_stock', null, [
                'attr' => [
                    'min' => 0,
                    'placeholder' => 'Quantité en stock'
                ],
                'constraints' => [
                    new PositiveOrZero([
                        'message' => 'La quantité ne peut pas être inférieure zéro'
                    ]),
                ]
            ])
            ->add('category');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
