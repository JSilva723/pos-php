<?php

declare(strict_types=1);

namespace Tenant\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tenant\Entity\Client;
use Tenant\Entity\PriceList;
use Tenant\Entity\SaleOrder;

class SaleOrderType extends StyledType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'class' => Client::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a client',
            ])
            ->add('priceList', EntityType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'class' => PriceList::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a price list',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SaleOrder::class,
        ]);
    }
}
