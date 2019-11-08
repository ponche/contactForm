<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Department;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom : ',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'votre nom: ',
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Votre mail : ',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message :',
            ])
            ->add('department', EntityType::class, [
                'label' => 'destination du message',
                'class' => Department::class,
                'choice_label' => 'name',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
