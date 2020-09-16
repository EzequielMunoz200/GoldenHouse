<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(PropertyRepository $propertyRepository)
    {
        return $this->render('main/home.html.twig', [
            'properties' => $propertyRepository->findLatest(),
        ]);
    }
}
