<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use AppBundle\Entity\Images;
use AppBundle\Form\ImageType;

/**
  * @IsGranted("IS_AUTHENTICATED_FULLY")
  */
class ImagesController extends Controller
{
    const PAGINATOR_START_PAGE = 1;
    const PAGINTATOR_ROWS_PER_PAGE = 5;
    
    /**
     * @Route("/", name="image_list")
     */
    public function listAction(Request $request): Response
    {
        $images = $this->getDoctrine()->getRepository(Images::class)->findAll();
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $images,
            $request->query->getInt('page', self::PAGINATOR_START_PAGE), self::PAGINTATOR_ROWS_PER_PAGE);

        return $this->render('images/list.html.twig', ['pagination' => $pagination]);
    }
    
    /**
     * @Route("/add", name="image_add", methods={"GET", "POST"})
     */
    public function addAction(Request $request): Response
    {
        $image = new Images();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $fileService App\Service\FileService */
            $fileService = $this->get('file_handler');
            $imageName = $fileService->upload($form->get('image')->getData());
            $image->setName($form["name"]->getData()); //in case we had CRUD, ImagesService would be appropriate
            $image->setUserId($this->getUser());
            $image->setTimestamp(new \DateTime());
            $image->setServername($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('image_list');
        }
        
        return $this->render('images/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
