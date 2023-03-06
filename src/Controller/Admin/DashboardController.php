<?php

/*
This is the DashboardController.
This is what is seen at the admin page's dashboard.
Please look at https://symfony.com/bundles/EasyAdminBundle/current/dashboards.html
for more info.
*/

/*
Helpful resource: https://dev.to/nabbisen/easyadmin-4-based-on-php-8-and-symfony-6-install-and-create-a-sample-4gcg
The SymfonyCasts.com tutorial series is also useful.
*/

namespace App\Controller\Admin;

// entities
use App\Entity\Food;
use App\Entity\User;

// easy admin bundle items
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

// http actions
use Symfony\Component\HttpFoundation\Response;

// routing
use Symfony\Component\Routing\Annotation\Route;

/*
Note: users need to have ROLE_ADMIN to access 
*/

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // render your own custom html here.
        // NOTE: there is some important extending that has to be done.
        // see https://stackoverflow.com/questions/38829397/how-to-setup-a-custom-form-page-within-easyadminbundle
        // for more.
        return $this->render('admin/index.html.twig');

        // the following came written when this page was generated: 

        // * Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // * $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // * return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // * Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // * if ('jane' === $this->getUser()->getUsername()) {
        // *   return $this->redirect('...');
        // *}

        // * Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // * (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        // *
        // * return $this->render('some/path/my-dashboard.html.twig');
    }

    // for setting configs of the admin dashboard
    // mainly for display
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SWITCH Study');
    }

    // for setting the items shown on the menu 
    // mainly for display
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Food','fas fa-list',Food::class);
        yield MenuItem::linkToCrud('User','fas fa-list',User::class);

        // homepage
        // use the route name of the page you want to link to
        yield MenuItem::linkToUrl("Homepage",'fas fa-list',$this->generateUrl('MLifeController__index'));
    }

    // adding custom actions to the admin page
    // see here: https://symfony.com/bundles/EasyAdminBundle/current/actions.html
    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX,Action::DETAIL);
    }
}
