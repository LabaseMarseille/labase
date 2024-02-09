<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Rolesuser;

use App\Repository\RolesuserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = $options['roles'];

        dump($roles);

        dump(array_search('ROLE_REFBASE',$roles));


        $builder
                ->add('nom', TextType::class,array('label' => "Indiquez votre nom, prénom ou pseudo.", 'required'=>false))

                ->add('telephone', TextType::class,array('label' => "Indiquez votre téléphone (utilisé pour d'éventuels échanges avec la progd de la Base).", 'required'=>false))
              /*  ->add('password', RepeatedType::class, array( 'label'=>'Mot de passe',
                    'type' => PasswordType::class,
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ))*/



            ->add('cloture', ChoiceType::class, [
                'label' => 'Cloturer ?',
                'choices' => ['choices' => ['Non' => '0', 'Oui' => '1']],
                'attr' => ['class' => 'form-inline checkbox-inline'],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
        ]);

        if (array_search('ROLE_ADMIN',$roles)===0 or array_search('ROLE_ADMIN',$roles)>0 ){
            dump($roles);

            dump(array_search('ROLE_ADMIN',$roles));
            $builder

                ->add('rolesusers', EntityType::class, [
                    'class'     => Rolesuser::class,
                    'choice_label' => 'libelle',
                    'required'=> false,
                    'query_builder' => function(RolesuserRepository $pr) {
                        return $pr->createQueryBuilder('p')
                            ->select('p')
                            ->where('p.cloture=false');
                    },
                    'label'     => 'Attribution des droits',
                    'expanded'  => true,
                    'multiple'  => true,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, 'roles'=>[]
        ]);
    }
}
