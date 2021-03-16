<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('stock')
            ->add('tva')
            ->add('remise')
            ->add('message')
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'multiple' => false,
                'expanded' => true,
                'choice_label' => 'libelle'
            ])
            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'multiple' => false,
                'expanded' => true,
                'choice_label' => 'libelle'
            ])
            ->add('provider', EntityType::class, [
                'class' => User::class,
                'multiple' => false,
                'expanded' => true,
                'choice_label' => 'username'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
