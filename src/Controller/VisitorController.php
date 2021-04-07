<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitorController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('visitor/home.html.twig');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $data = $form->getData();
            return $this->redirectToRoute('result',
                [
                    "result"=>$data['Search']
                ]);
        }
        return $this->render('visitor/search.html.twig', [
            "SearchForm"=>$form->createView()
        ]);
    }

    /**
     * @Route("/result", name="result")
     */
    public function result(RequestStack $request)
    {
        $keywoord = $request->getCurrentRequest()->query->get('result');
        $product = $this->getDoctrine()->getRepository(Product::class)->Search($keywoord);
        return $this->render('visitor/product.html.twig',
            [
                "products"=>$product
            ]);
    }

    /**
     * @Route("/product", name="product")
     */
    public function product()
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('visitor/product.html.twig',
            [
                "products"=>$product
            ]);
    }

    /**
     * @Route("/product/category/{categoryId}", name="category")
     */
    public function category($categoryId)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findCategory($categoryId);

        return $this->render('visitor/product.html.twig',
            [
                "products"=>$product
            ]);
    }

    /**
     * @Route("/product/parent/{pId}", name="parent")
     */
    public function parent($pId)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findCategoryParent($pId);

        return $this->render('visitor/product.html.twig',
            [
                "products"=>$product
            ]);
    }




}
