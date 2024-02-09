<?php

namespace App\Form;

use App\Entity\Collectif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('nom', TextType::class,array('label' => "Indiquez le nom complet de votre collectif/association. *", 'required'=>true))
            ->add('abreviation', TextType::class,array('label' => "Indiquez une abréviation à donner à votre collectif/association.", 'required'=>false))
            ->add('telephone', TextType::class,array('label' => "Indiquez un téléphone.", 'required'=>false))
            ->add('mail', EmailType::class,array('label' => "Indiquez un e-mail.", 'required'=>false))
            ->add('siret', TextType::class,array('label' => "Indiquez un siret.", 'required'=>false))
            ->add('codepostal', TextType::class,array('label' => "Indiquez un code postal.", 'required'=>false))

            ->add('confidentiel', ChoiceType::class, [
                'label' => 'Si vous préférez que votre collectif/association n\'apparaisse pas dans la liste ni ne soit trouvé par la recherche, indiquez "Oui"',
                'choices' => ['choices' => ['Non' => '0', 'Oui' => '1']],
                'attr' => ['class' => 'form-inline checkbox-inline'],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Collectif::class,
        ]);
    }
}
