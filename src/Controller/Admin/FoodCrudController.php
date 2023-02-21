<?php

namespace App\Controller\Admin;

use App\Entity\Food;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FoodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Food::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
