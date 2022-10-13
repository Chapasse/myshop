<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreCrudController extends AbstractCrudController
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        // je crée un constructeur pour appeler le service UserPasswordHasherInterface
        $this->hasher = $hasher;
    }
    public static function getEntityFqcn(): string
    {
        return Membre::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('pseudo'),
            TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class)->onlyWhenCreating(),
            TextField::new('nom', 'Nom de famille'),
            TextField::new('prenom', 'Prénom'),
            TextField::new('email'),
            ChoiceField::new('civilite')->setChoices(['masculin'=>'m','feminin' => 'f', 'transgenre' => 'trans']),
            CollectionField::new('roles')->setTemplatePath('admin/field/roles.html.twig'),
            DateTimeField::new('date_enregistrement')->setFormat("d/M/Y à H:m:s")->renderAsNativeWidget()->hideOnForm(),
        ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // $entityInstance correspond à $user
        if (!$entityInstance->getId()) 
        {
            $entityInstance->setPassword(
                $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword())
            );
        }
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
    public function createEntity(string $entityFqcn)
    {
        $membre = new $entityFqcn; 
        $membre->setDateEnregistrement(new \DateTime);
        return $membre;
    }

}
