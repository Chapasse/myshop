<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use App\Entity\Product;
use App\Entity\Commande;
use App\Controller\Admin\MembreCrudController;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('id_membre')->renderAsNativeWidget(),
            AssociationField::new('id_product')->renderAsNativeWidget(),
            IntegerField::new('quantite'),
            NumberField::new('montant')->setNumDecimals(2),
            ChoiceField::new('etat')->setChoices(['en cours  de traitement'=>'en cours  de traitement','envoyé'=>'envoye','livré'=>'livre']),
            DateTimeField::new('date_enregistrement')->setFormat("d/M/Y à H:m:s")->hideOnForm(),    
        ];
    }
    
}
