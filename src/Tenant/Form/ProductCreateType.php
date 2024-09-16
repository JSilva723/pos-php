<?php

declare(strict_types=1);

namespace Tenant\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Tenant\Config\UnitOfMeasure;
use Tenant\Entity\Category;
use Tenant\Entity\PriceList;
use Tenant\Entity\Product;

use function array_combine;
use function array_map;

class ProductCreateType extends StyledType
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
            ->add('price', TextType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR . ' input-float'],
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
            ->add('brand', TextType::class, [
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
                'label' => 'Stock',
            ])
            ->add('stockMin', NumberType::class, [
                'label' => 'Stock minimum',
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ],
            ])
            ->add('unitOfMeasure', ChoiceType::class, [
                'label' => 'Unit of measure',
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'choices' => array_combine(
                    array_map(fn (UnitOfMeasure $uom) => $uom->getLabel(), UnitOfMeasure::cases()),
                    UnitOfMeasure::cases(),
                ),
                'required' => true,
            ])
            ->add('isFractionable', CheckboxType::class, [
                'label' => 'Is fractionable',
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_CHECK_ATTR],
                'required' => false,
                'mapped' => false,
            ])
            ->add('category', EntityType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.isEnable = :enabled')
                        ->setParameter('enabled', true);
                },
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
            ])
            ->add('priceList', EntityType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'class' => PriceList::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.isEnable = :enabled')
                        ->setParameter('enabled', true);
                },
                'choice_label' => 'name',
                'placeholder' => 'Select a price list',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
