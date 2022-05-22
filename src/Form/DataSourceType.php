<?php

namespace App\Form;

use App\Entity\DataSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataSourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'form.data_source_type.label',
                'required' => true,
            ])
            ->add('equivalence', TextType::class, [
                'label' => 'form.data_source_type.equivalence',
                'required' => true,
            ])
        ;

        $builder->get('equivalence')->addModelTransformer(new CallbackTransformer(
            function ($equivalencesAsArray) {
                return json_encode($equivalencesAsArray);
            },
            
            function ($equivalencesAsString) {
                return json_decode($equivalencesAsString, true);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DataSource::class,
        ]);
    }
}
