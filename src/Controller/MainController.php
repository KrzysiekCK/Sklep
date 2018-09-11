<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Types;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController {
    
    /**
     * @Route("/home", name="app_homepage")
     */
    public function homepage() {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Types::class)->findBy(array(), array('name' => 'ASC'));
        return $this->render('homepage.html.twig', [
            'title' => 'PTAK Moda Dla Ciebie',
            'types' => $types
        ]);
    }

    /**
     * @Route("/products/{typesid}", name="app_products")
     */
    public function products($typesid) {

        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Types::class)->findBy(array(), array('name' => 'ASC'));
        if ($typesid == 'all') $typesid = $types;
        $products = $em->getRepository(Products::class)->findBy(array('type' => $typesid), array('name' => 'ASC'));

        $images = array();
        foreach ($products as $key => $entity) {
            $images[$key] = base64_encode(stream_get_contents($entity->getImage()));
        }

        return $this->render('products.html.twig', [
            'title' => 'Produkty',
            'products' => $products,
            'types' => $types,
            'images' => $images
        ]);
    }

    /**
     * @Route("/product-details/{id}", name="app_product_details")
     */
    public function productDetails($id) {

        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Types::class)->findBy(array(), array('name' => 'ASC'));
        $product = $em->getRepository(Products::class)->findOneBy(array('id' => $id));

        // $images = array();
        // foreach ($products as $key => $entity) {
        //     $images[$key] = base64_encode(stream_get_contents($entity->getImage()));
        // }

        return $this->render('productdetails.html.twig', [
            'title' => $product->getName(),
            'product' => $product,
            'types' => $types
        ]);
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact() {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Types::class)->findBy(array(), array('name' => 'ASC'));
        return $this->render('contact.html.twig', [
            'title' => 'Kontakt',
            'types' => $types
        ]);
    }

}