<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\TeamInvitation;
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
class TeamInvitationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TeamInvitation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Invitations')
            ->setEntityLabelInSingular('Invitation');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('team');
        yield AssociationField::new('user');
        yield DateTimeField::new('creationDate');
        yield TextField::new('user_email');
    }
}