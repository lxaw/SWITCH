<?php

namespace App\Controller\Admin;

namespace App\Controller\Admin;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;

class UserCrudController extends AbstractCrudController
{
    public const ACTION_DUPLICATE = "duplicate";

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // configurable fields
    // see: https://symfony.com/bundles/EasyAdminBundle/current/fields.html
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("username"),
            EmailField::new('email'),
            ArrayField::new('roles'),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        $saveCsv = Action::new('saveCsv','Save as Csv')
            ->displayAsButton()
            ->linkToCrudAction('saveUsersToCsv')
            ->createAsGlobalAction();
        $duplicate = Action::new(self::ACTION_DUPLICATE)
            ->linkToCrudAction('duplicateProduct')
            ->setCssClass('btn btn-info');


        return $actions
            ->add(Crud::PAGE_EDIT,$duplicate)
            ->add(Crud::PAGE_INDEX,$saveCsv);
    }
        public function duplicateProduct(
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $em
    ): Response {
        /** @var User $product */
        $product = $context->getEntity()->getInstance();

        $duplicatedProduct = clone $product;

        parent::persistEntity($em, $duplicatedProduct);

        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicatedProduct->getId())
            ->generateUrl();

        return $this->redirect($url);
    }
    // https://stackoverflow.com/questions/27888374/create-csv-and-force-download-of-file
    public function saveUsersToCsv(
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManager $em
    ): Response{
        $instance = $context->getEntity()->getInstance();
        
        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->generateUrl();
        return $this->redirect($url);;;;
    }
}
