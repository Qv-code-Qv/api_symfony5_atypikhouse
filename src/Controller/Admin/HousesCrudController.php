<?php

namespace App\Controller\Admin;

use App\Entity\Houses;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HousesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Houses::class;
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
