<?php

namespace App\Form;

use App\Entity\DataSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DataSourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class)
            ->add('equivalence', TextType::class)
        ;

        $builder->get('equivalence')->addModelTransformer(new CallbackTransformer(
            // ['source_1' => 'dest_1', 'source_2' => 'data_2'] -> source_1-dest_1 ; source_2-dest_2
            function ($equivalencesAsArray) {
                $sourceDest = [];
                foreach ($equivalencesAsArray as $source => $dest) {
                    $sourceDest[] = $source . '-' . $dest;
                }

                return implode(' ; ', $sourceDest);
            },
            
            // source_1-dest_1 ; source_2-dest_2 -> ['source_1' => 'dest_1', 'source_2' => 'data_2']
            function ($equivalencesAsString) {
                $equivalencesAsArray = [];
                foreach (array_map('trim', explode(';', $equivalencesAsString)) as $equivalence) {
                    $sourceDest = array_map('trim', explode('-', $equivalence));
                    $equivalencesAsArray[$sourceDest[0]] = $sourceDest[1];
                }

                return $equivalencesAsArray;
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
