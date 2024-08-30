<?php

declare(strict_types=1);

namespace Tenant\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Tenant\Entity\Category;
use Tenant\Entity\Product;

class ProductType extends StyledType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 100]),
                ],
            ])
            ->add('sku', TextType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'constraints' => [
                    new Length(['max' => 100]),
                ],
                'required' => false,
            ])
            ->add('stockQuantity', NumberType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ],
            ])
            ->add('category', EntityType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
