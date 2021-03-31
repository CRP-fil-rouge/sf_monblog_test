<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductListController extends AbstractController
{
    /**
     * @Route("/product/list", name="product_list")
     */
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        // on récupère tous les produits de la table product dans la bdd avec la méthode findAll() de la classe ProductRepository
        // on stocke cette liste dans la variable $productList sous forme de tableau contenant des objets
        $dataList = $productRepository->findAll();
        $productListPhp = $paginator->paginate(
            $dataList,
            $request->query->getInt('page',1),
            5
        );
        // on pourrait aussi implémenter de la sorte suivante, sans mettre de paramètre dans la fonction index()
        // $productListPhp = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $categoryListPhp = $categoryRepository->findAll();
        // on envoie la la variable $productListPhp vers le template par l'intermédiaire du paramètre 'productListTwig'
        return $this->render('product_list/index.html.twig', [
            'productListTwig' => $productListPhp,
            'categoryListTwig' => $categoryListPhp
        ]);
    }

    /**
     * @Route("/product/list_by_category/{id}", name="product_list_by_category")
     */
    public function byCategory(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator, $id): Response
    {
        // on récupère les produits de la table product dans la bdd, pour une certaine catégorie, avec la méthode "findBy()" de la classe ProductRepository
        // cette méthode demande en paramètre un tableau qui contient les critère nécessaire à la recherche dans la bdd
        // on stocke cette liste dans la variable $productList sous forme de tableau contenant des objets

        // on peut ajouter un ordre de rangement des résultats et une quantité de résultats à ne pas dépasser
        $dataList = $productRepository->findBy(['category' => $id], ['price' => 'DESC'],2);
        $productListPhp = $paginator->paginate(
            $dataList,
            $request->query->getInt('page',1),
            5
        ); 
        $categoryListPhp = $categoryRepository->findAll();

        return $this->render('product_list/index.html.twig', [
            'productListTwig' => $productListPhp,
            'categoryListTwig' => $categoryListPhp
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_single")
     */
    public function productSingle(ProductRepository $productRepository, $id): Response
    {
        $productPhp = $productRepository->find($id);

        return $this->render('product_list/single.html.twig', [
            'productTwig' => $productPhp
        ]);
    }

    /**
     * @Route("/product/name/{value}", name="find_by_name")
     */
    public function byName(Request $request, ProductRepository $productRepository, $value, PaginatorInterface $paginator, CategoryRepository $categoryRepository)
    {
        $categoryListPhp = $categoryRepository->findAll();

        $dataList = $productRepository->findByName($value);
        $productList = $paginator->paginate(
            $dataList,
            $request->query->getInt('page',1),
            5
        ); 
        return $this->render('product_list/index.html.twig', [
            'productListTwig' => $productList,
            'categoryListTwig' => $categoryListPhp
        ]);
    }

}
