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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('file', FileType::class, [
                'label' => 'Illustration',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
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
