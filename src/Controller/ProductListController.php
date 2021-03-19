<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductListController extends AbstractController
{
    /**
     * @Route("/product-list", name="product_list")
     */
    public function index(): Response
    {
        $productList = $this->getDoctrine()->getRepository(Product::class)->findBy([],['name' =>'ASC']);
        $categoryList = $this->getDoctrine()->getRepository(Category::class)->findBy([],['libelle' =>'ASC']);

        return $this->render('product_list/index.html.twig', [
            'productList' => $productList,
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * @Route("/product-list-bycategory/{id}", name="product_list_bycategory")
     */
    public function byCategory($id): Response
    {
        $productList = $this->getDoctrine()->getRepository(Product::class)->findBy(['category' => $id],['name' =>'ASC']);
        $categoryList = $this->getDoctrine()->getRepository(Category::class)->findBy([],['libelle' =>'ASC']);

        return $this->render('product_list/index.html.twig', [
            'productList' => $productList,
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_single")
     */
    public function product($id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['id' => $id]);
        $categoryList = $this->getDoctrine()->getRepository(Category::class)->findBy([],['libelle' =>'ASC']);

        return $this->render('product_list/product_single.html.twig', [
            'product' => $product,
            'categoryList' => $categoryList,
        ]);
    }
}
