<?php

namespace App\Controller;

use App\Entity\Color;
use App\Entity\Magazine;
use App\Entity\Type;
use App\Form\Filter;
use App\Form\Search;
use function Sodium\add;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
    public function products(Request $request, $type) {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));
        $colors = $em->getRepository(Color::class)->findBy(array(), array('name' => 'ASC'));

        $typesArray = array();
        foreach ($types as $key => $value) {
            if ($type == $value->getId()) {
                $typesArray += [$value->getId() => true];
            } else {
                $typesArray += [$value->getId() => false];
            }
        }
        $colorsArray = array();
        foreach ($colors as $key => $value) {
            $colorsArray += [$value->getId() => false];
        }
        $sizesArray = array(
            false, false, false, false, false, false
        );

        $new = false;
        $sale = false;
        if ($type == 'new') $new = true;
        if ($type == 'sale') $sale = true;
        $offersArray = array($new, $sale);

        $filter = new Filter();
        $filter->setOffers($offersArray);
        $filter->setTypes($typesArray);
        $filter->setColors($colorsArray);
        $filter->setSizes($sizesArray);



        $magazines = $em->getRepository(Magazine::class)->findByFilters($filter);

        $filterForm = $this->createFormBuilder($filter)
            ->add('offers', CollectionType::class, array('entry_type' => CheckboxType::class, 'allow_add' => true))
            ->add('types', CollectionType::class, array('entry_type' => CheckboxType::class, 'allow_add' => true))
            ->add('colors', CollectionType::class, array('entry_type' => CheckboxType::class, 'allow_add' => true))
            ->add('sizes', CollectionType::class, array('entry_type' => CheckboxType::class, 'allow_add' => true))
            ->add('price', RangeType::class, array('attr' => array('min' => 5,'max' => 50)))
            ->getForm();

        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted()) {
            $filter = $filterForm->getData();
            $magazines = $em->getRepository(Magazine::class)->findByFilters($filter);
        }


        $search = new Search();
        $searchForm = $this->createFormBuilder($search)
            ->add('search', SearchType::class)
            ->getForm();

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $search = $searchForm->getData();
            $magazines = $em->getRepository(Magazine::class)->findBySearch($search);
        }


        $images = array();
        foreach ($magazines as $key => $entity) {
            $images += [$entity->getId() => base64_encode(stream_get_contents($entity->getImage()))];
        }

        return $this->render('products.html.twig', [
            'title' => 'Produkty',
            'magazines' => $magazines,
            'types' => $types,
            'colors' => $colors,
            'images' => $images,
            'id' => $type,
            'filter' => $filterForm->createView(),
            'search' => $searchForm->createView()
        ]);
    }


    /**
     * @Route("/details/{id}", name="app_details")
     */
    public function productDetails($id) {

        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));
        $magazine = $em->getRepository(Magazine::class)->findOneBy(array('id' => $id));
        $magazines = $em->getRepository(Magazine::class)->findBy(array('product' => $magazine->getProduct()->getId()));

        // $images = array();
        // foreach ($products as $key => $entity) {
        //     $images[$key] = base64_encode(stream_get_contents($entity->getImage()));
        // }

        $image = base64_encode(stream_get_contents($magazine->getImage()));

        return $this->render('details.html.twig', [
            'title' => $magazine->getProduct()->getName(),
            'magazine' => $magazine,
            'magazines' => $magazines,
            'types' => $types,
            'image' => $image
        ]);
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact() {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));
        return $this->render('contact.html.twig', [
            'title' => 'Kontakt',
            'types' => $types
        ]);
    }

}