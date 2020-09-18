<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/property")
 */
class PropertyController extends AbstractController
{
    /**
     * @Route("/biens", name="property_index", methods={"GET"})
     */
    public function index(PropertyRepository $propertyRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);
        
        $properties = $paginator->paginate(
            $propertyRepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
            
        );
        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'current_menu' => 'properties',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property_show", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET"})
     */
    public function show(Property $property, $slug, $id): Response
    {
        $propSlug = $property->getSlug();
        if ($propSlug !== $slug) {
            return $this->redirectToRoute('property_show', [
                'id' => $property->getId(),
                'slug' => $propSlug,
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'options' => $property->getOptions()
        ]);
    }

}
