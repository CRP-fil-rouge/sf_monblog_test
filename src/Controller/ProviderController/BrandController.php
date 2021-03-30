<?php

namespace App\Controller\ProviderController;

use App\Entity\Brand;
use App\Form\ProviderBrandType;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/provider/brand")
 * @IsGranted("ROLE_PROVIDER")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("/", name="provider_brand_index", methods={"GET"})
     */
    public function index(): Response
    {
        // on récupère le user connecté pour n'afficher que ses marques à lui
        $user = $this->getUser();
        $brandList = $user->getBrands();
        
        return $this->render('provider/brand/index.html.twig', [
            'brands' => $brandList
        ]);
    }

    /**
     * @Route("/new", name="provider_brand_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $brand = new Brand();
        $form = $this->createForm(ProviderBrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brand->setProvider($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($brand);
            $entityManager->flush();

            return $this->redirectToRoute('provider_brand_index');
        }

        return $this->render('provider/brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="provider_brand_show", methods={"GET"})
     */
    public function show(Brand $brand): Response
    {
        return $this->render('provider/brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="provider_brand_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Brand $brand, BrandRepository $brandRepository): Response
    {
        // on récupère l'id du user qui a créé la marque en cours
        $provider = $brand->getProvider();
        $providerId = $provider->getId();
        echo 'id du créateur de la marque en cours :';
        var_dump($providerId);

        // on récupère l'id du user connecté
        $user = $this->getUser();
        $id = $user->getId();
        echo 'id du user connecté :';
        var_dump($id);

        // on va comparer les 2 pour savoir si le user connecté a les droits pour modifier la marque en cours

        if($providerId == $id) {
            $form = $this->createForm(ProviderBrandType::class, $brand);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('provider_brand_index');
            }

            return $this->render('provider/brand/edit.html.twig', [
                'brand' => $brand,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('provider_brand_index');
        }
    }

    /**
     * @Route("/{id}", name="provider_brand_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Brand $brand): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('provider_brand_index');
    }
}
