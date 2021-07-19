<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RH;
use App\Repository\RHRepository;



class DefaultController extends AbstractController
{
    private $rhRepository;
    public function __construct(RHRepository $rhRepository)
    {
        $this->RHRepository = $rhRepository;
    }
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        $rhs = $this->rhRepository->findAll();

        return $this->render('default/index.html.twig', [
            'rhs' => $rhs
        ]);
    }
}
