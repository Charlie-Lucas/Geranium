<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/api/users/me", name="get_me")
     */
    public function getMe(): Response
    {
        $router = $this->get('router')->getRouteCollection()->get('api_users_get_item');
        $defaults = $router->getDefaults();
        return $this->forward($router->getDefault('_controller'), array_merge($defaults, [ 'id' => $this->getUser()->getId()]));
    }
    /**
     * @Route("/{reactRouting}", name="home", defaults={"reactRouting": null})
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
