<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class UserCrudController extends AbstractCrudController
{
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
        $saveCsv = Action::new('saveCsv','Save as Csv', 'fa fa-save-as-csv')
            ->displayAsButton()
            ->linkToCrudAction('saveUsersToCsv');

        return $actions
            ->add(Crud::PAGE_DETAIL,$saveCsv);
    }
    // https://stackoverflow.com/questions/27888374/create-csv-and-force-download-of-file
    public function saveUsersToCsv(AdminContext $context){
        // $instance = $context->getEntity()->getInstance();

        $fileName = "test";
        $filePath = $_SERVER["DOCUMENT_ROOT"] . $fileName . '.csv';
        $output = fopen($filePath,'w+');
        fputcsv($output,array("Number","Description","test"));
        fputcsv($output,array("100","TestDescription","10"));

        // set the headers
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename ="'.$fileName.".csv'");
        header('Content-Length: ' .filesize($filePath));
        echo readfile($filePath);
    }
}
