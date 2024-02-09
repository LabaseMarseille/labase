<?php

namespace App\Form;

use App\Entity\Objectif;
use App\Entity\Reservation;
use App\Entity\Collectif;
use App\Entity\Statutevent;
use App\Entity\Besoinbar;
use App\Entity\Salle;
use App\Entity\Besoin;
use App\Entity\User;
use App\Entity\Mailreservation;
use App\Entity\Typereservation;
use App\Repository\BesoinbarRepository;
use App\Repository\MailreservationRepository;
use App\Repository\StatuteventRepository;
use App\Repository\CollectifRepository;
use App\Repository\BesoinRepository;
use App\Repository\TypereservationRepository;
use App\Repository\ObjectifRepository;
use App\Repository\SalleRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $by = $options['by'];
        $builder
            ->add('personnepresente', TextType::class, ['label' => 'Nom/Pseudo d\'une personne référente (présente le jour J)'])
            ->add('titre', TextType::class, ['label' => 'Titre de la réservation (affiché dans notre programme) ex : Réunion **** '])
            ->add('nbpersonne', IntegerType::class, ['label' => 'Combien de personnes attendez-vous ? (maximum,pour déterminer quelle salle conviendra le mieux)'])
            ->add('gratuit', ChoiceType::class, [
                'label' => 'L\'événement est-il gratuit pour les personnes y participant ?',
                'choices' => ['choices' => ['Non' => '0', 'Oui' => '1']],
                'attr' => ['class' => 'form-inline checkbox-inline'],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            //
            ->add('refbase', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', 'required'=>false,
                'placeholder' => '-- Choisissez --',
                'query_builder' => fn(UserRepository $pr) => $pr->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.cloture=false and p.roles like :val')
                    ->setParameter('val', '%ROLE_REFBASE%'),
                'label' => 'Si un.e référent Base de votre groupe assure l\'ouverture pour cet événement, précisez son nom => '
            ])

            ->add('comm', CheckboxType::class, [
                'label' => 'Souhaitez-vous que la Base communique sur votre évènement ?', 'required' => false,
                'attr' => ['class' => 'form-inline checkbox-inline'],
            ])

            ->add('datedebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de début',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('datefin', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de fin',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('autredate', DateTimeType::class, [
                'widget' => 'single_text', 'required'=>false,
                'label' => 'Si le créneau est déjà réservé, l\'événement est-il envisageable à une date ultérieure ? Si oui à quelle date ?',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('formulation', CKEditorType::class, array('config' => array('uiColor' => '#ffffff'),
                'label' => 'Formule ton événement comme si tu le présentais sur les réseaux ', ))
            ->add('description', CKEditorType::class, array('config' => array('uiColor' => '#ffffff'),
                'label' => 'Description de l\'événement (et si possible, pourquoi la Base ?)', ))
            ->add('autreinfo', CKEditorType::class, array('config' => array('uiColor' => '#ffffff'),
                'label' => 'Autres informations/remarques pouvant nous aider à organiser l\'événement ?', ))

            ->add('typereservation', EntityType::class, [
                'class' => Typereservation::class,
                'choice_label' => 'libelle',
                'placeholder' => '-- Choisissez --',
                'query_builder' => fn(TypereservationRepository $pr) => $pr->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.cloture=false'),
                'label' => 'Type de réservation'
            ])
            ->add('statutevent', EntityType::class, [
                'class' =>Statutevent::class,
                'choice_label' => 'libelle',
                'placeholder' => '-- Choisissez --',
                'query_builder' => fn(StatuteventRepository $pr) => $pr->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.cloture=false'),
                'label' => 'Statut de l\'évènement'
            ])
            ->add('collectif', EntityType::class, [
                'class' => Collectif::class,
                'choice_label' => 'nom',
                'placeholder' => '-- Choisissez --',
                'query_builder' => fn(CollectifRepository $pr) => $pr->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.cloture=false'),
                'label' => 'Collectif/Association'
            ])
            ->add('mailreservation', EntityType::class, [
                'class' => Mailreservation::class,
                'choice_label' => 'libelle',
                'placeholder' => '-- Choisissez --',
                'query_builder' => fn(MailreservationRepository $pr) => $pr->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.cloture=false'),
                'label' => 'Quel e-mail souhaitez vous utiliser pour les échanges de cette réservation ?'
            ])
            ->add('email', EmailType::class,array('label' => "Mail utilisé pour gérer cette réservation."))
            ->add('besoinbar', EntityType::class, [
                'class' => Besoinbar::class,
                'choice_label' => 'libelle',
                'placeholder' => '-- Choisissez --',
                'query_builder' => fn(BesoinbarRepository $pr) => $pr->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.cloture=false'),
                'label' => 'Aurez-vous besoin du Bar ? C\'est ce qui nous paye le loyer ;)'
            ])

            //->add('refbase')


            ->add('objectifs', EntityType::class, [
                'class'     => Objectif::class,
                'choice_label' => 'libelle',
                'required'=> false,
                'query_builder' => function(ObjectifRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->select('p')
                        ->where('p.cloture=false');
                },
                'label'     => 'Objectifs et intentions(multi-choix possible) ?',
                'expanded'  => true,
                'multiple'  => true,
            ])
            ->add('besoins', EntityType::class, [
                'class'     => Besoin::class,
                'choice_label' => 'libelle',
                'required'=> false,
                'query_builder' => function(BesoinRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->select('p')
                        ->where('p.cloture=false');
                },
                'label'     => 'Avez-vous des besoins techniques particuliers ?',
                'expanded'  => true,
                'multiple'  => true,
            ])
            ->add('autrebesoin', TextType::class, ['label' => 'Autre besoin ?', 'required'=>false,])

            ->add('doc1File', VichFileType::class, [
                'label' => 'Document complémentaire n°1',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Cocher et mettre à jour pour supprimer le fichier',
                'download_label' => 'Affichage',
                'asset_helper' => true,
            ])
            ->add('doc2File', VichFileType::class, [
                'label' => 'Document complémentaire n°2',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Cocher et mettre à jour pour supprimer le fichier',
                'download_label' => 'Affichage',
                'asset_helper' => true,
            ])
            ->add('doc3File', VichFileType::class, [
                'label' => 'Document complémentaire n°3',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Cocher et mettre à jour pour supprimer le fichier',
                'download_label' => 'Affichage',
                'asset_helper' => true,
            ])
        ;

        if ($by=='prog'){
            $builder->add('salles', EntityType::class, [
                'class'     => Salle::class,
                'choice_label' => 'libelle',
                'required'=> false,
                'query_builder' => function(SalleRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->select('p')
                        ->where('p.cloture=false');
                },
                'label'     => 'Attribution de salle(s)',
                'expanded'  => true,
                'multiple'  => true,
            ]);
        }


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,'by'=>[]
        ]);
    }
}
