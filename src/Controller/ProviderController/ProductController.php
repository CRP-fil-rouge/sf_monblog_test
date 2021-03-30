<?php

namespace App\Controller\ProviderController;

use App\Entity\Product;
use App\Form\ProviderProductType;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/provider/product")
 * @IsGranted("ROLE_PROVIDER")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="provider_product_index", methods={"GET"})
     */
    public function index(): Response
    {
        // on récupère le user connecté pour n'afficher que ses produits à lui
        $user = $this->getUser();
        $productList = $user->getProducts();
        return $this->render('provider/product/index.html.twig', [
            'products' => $productList,
        ]);
    }

    /**
     * @Route("/new", name="provider_product_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        // on récupère le user connecté...
        $user = $this->getUser();
        $product = new Product();
        $form = $this->createForm(ProviderProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //...pour l'associer au produit en cours de création
            $product->setProvider($user);
            $file = $form->get('file')->getData();
            if($file) {
                $fileName = $fileUploader->upload($file);
                $product->setFile($fileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('provider_product_index');
        }

        return $this->render('provider/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="provider_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="provider_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            if($file) {
                $fileName = $fileUploader->upload($file);
                $product->setFile($fileName);
            } else {
                $file = $product->getFile();
                $product->setFile($file);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="provider_product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
