<?php

namespace App\Controller\Admin;

use App\Entity\Restaurante;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Intl\Timezones;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;


class RestauranteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Restaurante::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
          //  IdField::new('id'),
            TextField::new('nombre'),
            TextField::new('direccion'),
            TextEditorField::new('descripcion'),
            DateTimeField::new('horario'),
            IntegerField::new('contato')
            // AssociationField::new('invitacion'),
        ];
    }
    
}
