<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextareaField::new('description')->setMaxLength(20),
            ChoiceField::new('couleur')->setChoices(['bleu'=> 'bleu','jaune' => 'jaune','vert' => 'vert','rouge' => 'rouge']),
            ChoiceField::new('taille')->setChoices(['XS'=> 'XS','S' => 'S','L' => 'L','XL' => 'XL']),
            ChoiceField::new('collection')->setChoices(['m'=>'masculin','f' => 'feminin', 'e' => 'enfant']),
            TextField::new('photo'),
            MoneyField::new('prix')->setCurrency('EUR'),
            IntegerField::new('stock'),
            DateTimeField::new('date_enregistrement')->setFormat("d/m/Y à H:m:s")->hideOnForm(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $product = new $entityFqcn; 
        $product->setDateEnregistrement(new \DateTime);
        return $product;
    }

    
}
