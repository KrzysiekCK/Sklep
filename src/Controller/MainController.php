<?php

namespace App\Controller;

use App\Entity\Magazine;
use App\Entity\Product;
use App\Entity\Type;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController {
    
    /**
     * @Route("/home", name="app_homepage")
     */
    public function homepage() {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));
        return $this->render('homepage.html.twig', [
            'title' => 'PTAK Moda Dla Ciebie',
            'types' => $types
        ]);
    }

    /**
     * @Route("/products/{type}", name="app_products")
     */
    public function products($type) {

        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));
        if ($type == 'all') {
            $magazines = $em->getRepository(Magazine::class)->findAll();
        } elseif ($type == 'new') {
            $magazines = $em->getRepository(Magazine::class)->findBy(array('new' => true));
        } elseif ($type == 'sale') {
            $magazines = $em->getRepository(Magazine::class)->findAllSaleProducts();
        } else {
            $magazines = $em->getRepository(Magazine::class)->findByType($type);
        }

        $images = array();
        foreach ($magazines as $key => $entity) {
            $images[$key] = base64_encode(stream_get_contents($entity->getImage()));
        }

        return $this->render('products.html.twig', [
            'title' => 'Produkty',
            'magazines' => $magazines,
            'types' => $types,
            'images' => $images
        ]);
    }

    /**
     * @Route("/product-details/{id}", name="app_product_details")
     */
    public function productDetails($id) {

        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));
        $magazine = $em->getRepository(Magazine::class)->findOneBy(array('id' => $id));

        // $images = array();
        // foreach ($products as $key => $entity) {
        //     $images[$key] = base64_encode(stream_get_contents($entity->getImage()));
        // }

        return $this->render('productdetails.html.twig', [
            'title' => $magazine->getProduct()->getName(),
            'magazine' => $magazine,
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