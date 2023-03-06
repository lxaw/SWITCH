<?php

/*
User CRUD Controller
Admin page
*/

namespace App\Controller\Admin;

namespace App\Controller\Admin;

// entities, querying
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
// actions
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

// easy admin bundle
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;


class UserCrudController extends AbstractCrudController
{
    // action name
    public const ACTION_SAVE_CSV = "SAVE_CSV";

    // entity manager
    private $em;

    // ctor
    public function __construct(EntityManagerInterface $em){
        // create the entity manager
        $this->em = $em;
    }
    

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
        // create and link a new action
        $saveCsv= Action::new(self::ACTION_SAVE_CSV)
            ->linkToCrudAction('saveUsersToCsv')
            ->setCssClass('btn btn-info')
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX,$saveCsv);
    }

    // save users to csv
    // saves all user entities
    public function saveUsersToCsv(
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $em
    ): Response {

        $userRepo = $this->em->getRepository(User::class);
        $users= $userRepo->findAll(); // Doctrine query
        $rows = array();
        $columns = array(
            'id',
            'email',
        );
        $rows[] = implode(',',$columns);
        foreach($users as $user){
            $data = array(
                $user->getId(),
                $user->getEmail(),
            );
            $rows[] = implode(',',$data);
        }
        $content = implode("\n",$rows);
        $response = new Response($content);
        $response->headers->set("Content-Type",'text/csv');
        $response->headers->set("Content-Disposition",'attachment; filename="users.csv"');

        return $response;
    }
}
