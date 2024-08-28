<?php

declare(strict_types=1);

namespace Tenant\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Tenant\Entity\Payment;

class PaymentType extends StyledType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'row_attr' => ['class' => self::ROW_ATTR],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 100]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
