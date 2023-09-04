<?php

namespace App\Form;

use App\Entity\Roster;
use App\Entity\User;
use App\Entity\Game;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateRosterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $team = $options['data']->getTeam();
        $builder
            ->add('game', EntityType::class, [
                'class' => Game::class,
                'label' => 'Jeu',
            ])
            ->add('members', EntityType::class, [
                'class' => User::class,
                'choices' => $team->getMembers(),
                'label' => 'Membres',
                'multiple' => true,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Créer le roster",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Roster::class,
        ]);
    }
}
