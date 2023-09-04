<?php

namespace App\Controller\Admin;

use App\Entity\Tournament;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class TournamentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tournament::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Tournois')
            ->setEntityLabelInSingular('Tournoi');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name')->setLabel("Nom");
        yield AssociationField::new('game')->setLabel("Jeu");
        yield AssociationField::new('rosters')->hideWhenCreating();
        yield NumberField::new('cashprize');
        yield NumberField::new('maxUsers');
        yield DateTimeField::new('date');
    }
}