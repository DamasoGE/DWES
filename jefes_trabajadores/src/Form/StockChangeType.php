<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\StockChange;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stockChange')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockChange::class,
        ]);
    }
}
