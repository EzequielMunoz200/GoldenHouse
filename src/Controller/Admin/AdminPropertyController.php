<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminPropertyController extends AbstractController
{
    /**
     * @Route("/biens", name="property_index", methods={"GET"})
     */
    public function index(PropertyRepository $propertyRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $properties = $paginator->paginate(
            $propertyRepository->findAll(),
            $request->query->getInt('page', 1),
            12
            
        );
        return $this->render('admin/property/index.html.twig', [
            'properties' => $properties,
            'current_menu' => 'index',
        ]);
    }

    /**
     * @Route("/property/new", name="property_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image) {

                $filepath = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('app.images_dir'),
                    $filepath
                );

                $img = new Image();
                $img->setImgPath($filepath);
                $property->addImage($img);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($property);
            $entityManager->flush();

            $this->addFlash('success', 'Bien ajouté avec success');

            return $this->redirectToRoute('admin_property_index');
        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'action' => 'new'
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
        return $this->render('admin/property/show.html.twig', [
            'property' => $property,
        ]);
    }

    /**
     * @Route("/property/{slug}-{id}/edit", name="property_edit", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET","POST"})
     */
    public function edit(Request $request, Property $property, $slug, $id): Response
    {
        /* $option = new Option();
        $property->addOption($option); */

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image) {

                $filepath = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('app.images_dir'),
                    $filepath
                );

                $img = new Image();
                $img->setImgPath($filepath);
                $property->addImage($img);
            }


            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Bien modifié avec success');

            return $this->redirectToRoute('property_show', ['slug' => $slug, 'id' => $id]);
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'options' => $property->getOptions(),
            'action' => 'edit',
            'images' => $property->getImages(),
        ]);
    }

    /**
     * @Route("/{id}", name="property_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Property $property): Response
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($property);
            $entityManager->flush();

            $this->addFlash('success', 'Bien supprimé avec success');
        }

        return $this->redirectToRoute('admin_property_index');
    }


    /**
     *@Route("/image/{id}", name="image_delete", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            $filepath = $image->getImgPath();

            unlink($this->getParameter('app.images_dir') . '/' . $filepath);

            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
