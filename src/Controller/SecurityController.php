<?php


namespace App\Controller;


use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
     * @Route("/api/logirgregdefg", name="app_login", methods={"POST"})

    public function login(Request $request): Response
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                "error" => "invalid login request"
                ], 400);

        }

        $user = $this->getUser();

        return $this->json([
            'username' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * @Route("/api/logout", name="app_logout")
     * @throws Exception

    public function logout(Request $request)
    {
        throw new Exception('should not be reached');
    }*/
}