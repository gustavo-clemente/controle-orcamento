<?php

namespace App\Controller;

use App\Entity\User;
use App\Trait\ControllerHelpers;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    use ControllerHelpers;

    // #[Route('/login', name: 'app_login')]
    // public function index(): Response
    // {
    //     $user = $this->getUser();
    //     $datetime = new DateTime();
    //     $issue_time = $datetime->getTimestamp();
    //     $expire_in = $datetime->modify('+30 minute')->getTimestamp();

    //     if($user === null){

    //         return new JsonResponse([

    //             'msg' => 'informe as credenciais para acesso'
    //         ],Response::HTTP_BAD_REQUEST);
    //     }

    //     $token = [

    //         'iat' => $issue_time,
    //         'exp' => $expire_in,
    //         'data' => [

    //             'id' =>$user->getUserIdentifier()
    //         ]
    //     ];

    //     return new JsonResponse($token);
    // }
}
