<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Reserva;
use App\Entity\Restaurante;
use App\Entity\Mesa;
use App\Entity\Opinion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\LocaleDto;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
//use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {

        //return parent::index();
        return $this->render('admin/index.html.twig');



        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bienvenido a la aministracion de Restaurantes');
            // ->setLocales([
            //     'en', // locale without custom options
            //     Locale::new('pl', 'polski', 'far fa-language') // custom label and icon
            // ]);
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToUrl('Restaurantes', 'fa fa-tags', '/restaurante/');
        yield MenuItem::linkToUrl('Perfil', 'fa fa-home', '/perfil');
        // yield MenuItem::section('Blog');
       // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        // yield MenuItem::subMenu('juegos', 'fa fa-article');
        // yield MenuItem::subMenu('Restaurante', 'fa fa-article');

        yield MenuItem::section('Mantenimiento');
        yield MenuItem::linkToCrud('Mesas', 'fa fa-tags', Mesa::class);
        yield MenuItem::linkToCrud('Reserva', 'fa fa-tags', Reserva::class);
        // yield MenuItem::linkToCrud('Restaurantes', 'fa fa-tags', Restaurante::class);
        yield MenuItem::linkToCrud('User', 'fa fa-tags', User::class);
        yield MenuItem::linkToCrud('Opinion', 'fa fa-tags', Opinion::class);
      
    }


    public function configureUserMenu(UserInterface $user): UserMenu
    {

       // $users = new User();

        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user);
            // use the given $user object to get the user name
           // ->setName($user->getFullName());

          //  -setNombre($users->getNombre());
            // use this method if you don't want to display the name of the user
           // ->displayUserName(false)

            // // you can return an URL with the avatar image
            // ->setAvatarUrl('https://...')
            // ->setAvatarUrl($user->getProfileImageUrl())
            // // use this method if you don't want to display the user image
            // ->displayUserAvatar(false)
            // // you can also pass an email address to use gravatar's service
            // ->setGravatarEmail($user->getMainEmailAddress())

            // you can use any type of menu item, except submenus
            // ->addMenuItems([
            //     MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
            //     MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
            //     MenuItem::section(),
            //     MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            // ]);
    }
}
