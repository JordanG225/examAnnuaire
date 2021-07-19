<?php

namespace App\Controller;

use App\Entity\RH;
use App\Form\RHType;
use App\Repository\RHRepository;
use App\Controller\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/r/h")
 */
class RHController extends AbstractController
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
       $this->slugger = $slugger;
    }
    /**
     * @Route("/", name="r_h_index", methods={"GET"})
     */
    public function index(RHRepository $rHRepository): Response
    {
        return $this->render('rh/index.html.twig', [
            'r_hs' => $rHRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="r_h_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rH = new RH();
        $form = $this->createForm(RHType::class, $rH);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
                $rh->setImage();
            }
            $rh->setNom($this->getUser());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rH);
            $entityManager->flush();

            return $this->redirectToRoute('r_h_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rh/new.html.twig', [
            'r_h' => $rH,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="r_h_show", methods={"GET"})
     */
    public function show(RH $rH): Response
    {
        return $this->render('rh/show.html.twig', [
            'r_h' => $rH,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="r_h_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RH $rH): Response
    {
        $form = $this->createForm(RHType::class, $rH);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('r_h_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rh/edit.html.twig', [
            'r_h' => $rH,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="r_h_delete", methods={"POST"})
     */
    public function delete(Request $request, RH $rH): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rH->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rH);
            $entityManager->flush();
        }

        return $this->redirectToRoute('r_h_index', [], Response::HTTP_SEE_OTHER);
    }
}
