<?php

namespace App\Form;

use App\Entity\Paciente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PacienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apellido')
            ->add('nombre')
            ->add('fecha_nac')
            ->add('lugar_nac')
            ->add('localidad_id')
            ->add('region_sanitaria_id')
            ->add('domicilio')
            ->add('tiene_documento')
            ->add('tipo_doc_id')
            ->add('numero')
            ->add('tel')
            ->add('nro_carpeta')
            ->add('obra_social_id')
            ->add('partido_id')
            ->add('genero')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Paciente::class,
        ]);
    }
}
