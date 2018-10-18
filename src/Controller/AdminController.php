<?php

namespace App\Controller;

use App\Entity\Color;
use App\Entity\Magazine;
use App\Entity\Product;
use App\Entity\Type;
use App\Form\ColorType;
use App\Form\MagazineType;
use App\Form\ProductType;
use App\Form\TypeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/magazine", defaults={"type":"magazines"}, name="app_admin_data_show")
     * @Route("/magazine/{type}", name="app_admin_data_show_by_type")
     */
    public function adminShowData(Request $request, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $magazines = $em->getRepository(Magazine::class)->findAll();
        $products = $em->getRepository(Product::class)->findAll();
        $types = $em->getRepository(Type::class)->findAll();
        $colors = $em->getRepository(Color::class)->findAll();

        $images = array();
        foreach ($magazines as $key => $entity) {
            $images += [$entity->getId() => base64_encode(stream_get_contents($entity->getImage()))];
        }

        return $this->render('admin/admindatashow.html.twig', [
            'title' => 'Magazyn',
            'type' => $type,
            'magazines' => $magazines,
            'images' => $images,
            'products' => $products,
            'types' => $types,
            'colors' => $colors,
        ]);
    }

    /**
     * @Route("/insert", defaults={"type":"magazines"}, name="app_admin_data_create")
     * @Route("/insert/{type}", name="app_admin_data_create_by_type")
     */
    public function adminCreateData(Request $request, $type)
    {
        $em = $this->getDoctrine()->getManager();

        $magazine = new Magazine();
        $formMagazine = $this->createForm(MagazineType::class, $magazine);

        $product = new Product();
        $formProduct = $this->createForm(ProductType::class, $product);

        $typeEntity = new Type();
        $formType = $this->createForm(TypeType::class, $typeEntity);

        $color = new Color();
        $formColor = $this->createForm(ColorType::class, $color);

        $formMagazine->handleRequest($request);
        $magazine = $formMagazine->getData();
        if ($formMagazine->isSubmitted() && $formMagazine->isValid()) {
            $em->persist($magazine);
            $em->flush();
            return $this->redirectToRoute('app_admin_data_show_by_type', array('type' => 'magazines'));
        }

        $formProduct->handleRequest($request);
        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('app_admin_data_show_by_type', array('type' => 'products'));
        }

        $formType->handleRequest($request);
        if ($formType->isSubmitted() && $formType->isValid()) {
            $em->persist($typeEntity);
            $em->flush();
            return $this->redirectToRoute('app_admin_data_show_by_type', array('type' => 'types'));
        }

        $formColor->handleRequest($request);
        if ($formColor->isSubmitted() && $formColor->isValid()) {
            $em->persist($color);
            $em->flush();
            return $this->redirectToRoute('app_admin_data_show_by_type', array('type' => 'colors'));
        }

        return $this->render('admin/admindatacreate.html.twig', [
            'title' => 'Dodawanie',
            'type' => $type,
            'formMagazine' => $formMagazine->createView(),
            'magazine' => $magazine,
            'formProduct' => $formProduct->createView(),
            'formType' => $formType->createView(),
            'formColor' => $formColor->createView(),
        ]);
    }

}
