<?php

namespace App\Form;

use App\Entity\Consulta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('articulacion_con_instituciones')
            ->add('internacion')
            ->add('diagnostico')
            ->add('observaciones')
            ->add('paciente')
            ->add('motivo')
            ->add('derivacion')
            ->add('tratamiento_farmacologico')
            ->add('acompanamiento')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consulta::class,
        ]);
    }
}
