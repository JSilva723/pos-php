<?php

declare(strict_types=1);

namespace Tenant\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tenant\Entity\Payment;
use Tenant\Entity\SaleOrder;

class SaleOrderCloseType extends StyledType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('payment', EntityType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'class' => Payment::class,
                'choice_label' => 'name',
                'label' => 'Payment method',
                'placeholder' => 'Select a payment method',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SaleOrder::class,
        ]);
    }
}
