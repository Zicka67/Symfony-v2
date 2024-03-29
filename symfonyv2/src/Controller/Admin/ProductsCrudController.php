<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextField::new('slug', 'Slug'),
            TextareaField::new('content', 'Contenu'),
            DateTimeField::new('created_at', 'Créé le')->setFormat('dd/MM/Y HH:mm:ss')
                ->setRequired(false),
            DateTimeField::new('updated_at', 'Mis à jour le')->setFormat('dd/MM/Y HH:mm:ss')
                ->setRequired(false),
            BooleanField::new('isBestseller', 'Meilleure vente'),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
            // Champ pour sélectionner les saveurs
            AssociationField::new('flavors', 'Saveurs')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }
    
}
