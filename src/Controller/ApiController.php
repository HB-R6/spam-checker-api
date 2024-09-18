<?php

namespace App\Controller;

use App\Repository\SpamDomainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    private const SPAM_DOMAINS = ["test.com", "free.fr", "10minutemail.com"];

    #[Route('/check', name: 'api_check_email', methods: ["POST"])]
    public function check(Request $request, SpamDomainRepository $spamDomains): JsonResponse
    {
        $data = $request->toArray();

        if (!isset($data['email'])) {
            return $this->json(
                ['error' => "L'email est obligatoire"],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            return $this->json(
                ['error' => "L'email est invalide"],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $email = $data['email'];
        $parts = explode("@", $email);
        $domain = $parts[1];

        if (in_array($domain, self::SPAM_DOMAINS) ||
            $spamDomains->findOneBy(['domain' => $domain]) !== null
        ) {
            return $this->json(['result' => 'spam']);
        }

        return $this->json(['result' => 'ok']);
    }
}
