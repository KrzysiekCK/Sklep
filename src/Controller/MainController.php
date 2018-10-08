<?php

namespace App\Controller;

use App\Entity\Color;
use App\Entity\Magazine;
use App\Entity\Product;
use App\Entity\Type;
use App\Form\Filter;
use App\Form\Search;
use function Sodium\add;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController {

    /**
     * @Route("/home", name="app_homepage")
     */
    public function homepage() {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));

        return $this->render('main/homepage.html.twig', [
            'title' => 'PTAK Moda Dla Ciebie',
            'types' => $types
        ]);
    }

    /**
     * @Route("/products/{type}", name="app_products")
     * @Route("/products/search", methods="GET", defaults={"type": "search"}, name="app_products_search")
     * @Route("/products/filter", methods="GET", defaults={"type": "filter"}, name="app_products_filter")
     */
    public function products(Request $request, $type) {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));
        $colors = $em->getRepository(Color::class)->findBy(array(), array('name' => 'ASC'));
        $priceMax = $em->getRepository(Product::class)->findByMaxPrice();

        $filter = $this->getFilter($request, $type, $types, $colors, $priceMax);

        if ($type == "search") {
            $magazines = $em->getRepository(Magazine::class)->findBySearch(new Search($request->get('search', '')));
        } else {
            $magazines = $em->getRepository(Magazine::class)->findByFilters($filter);
        }

        $images = array();
        foreach ($magazines as $key => $entity) {
            $images += [$entity->getId() => base64_encode(stream_get_contents($entity->getImage()))];
        }

        return $this->render('main/products.html.twig', [
            'title' => 'Produkty',
            'magazines' => $magazines,
            'types' => $types,
            'colors' => $colors,
            'images' => $images,
            'priceMax' => $priceMax,
            'filter' => $filter
        ]);
    }


    /**
     * @Route("product-details/{id}", name="app_details")
     */
    public function productDetails($id) {

        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository(Type::class)->findBy(array(), array('name' => 'ASC'));
        $magazine = $em->getRepository(Magazine::class)->findOneBy(array('id' => $id));
        $magazines = $em->getRepository(Magazine::class)->findBy(array('product' => $magazine->getProduct()->getId()));
        $image = base64_encode(stream_get_contents($magazine->getImage()));

        return $this->render('main/details.html.twig', [
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
        return $this->render('main/contact.html.twig', [
            'title' => 'Kontakt',
            'types' => $types
        ]);
    }

    private function getFilter(Request $request, $type, $types, $colors, $priceMax) {
        $typesArray = array();
        foreach ($types as $key => $value) {
            if ($type == $value->getId()) {
                $typesArray += [$value->getId() => true];
            } else {
                $typesArray += [$value->getId() => (boolean) $request->get('type'.$value->getId(), false)];
            }
        }
        $colorsArray = array();
        foreach ($colors as $key => $value) {
            $colorsArray += [$value->getId() => (boolean) $request->get('color'.$value->getId(), false)];
        }
        $sizesArray = array(
            (boolean) $request->get('unisize', false),
            (boolean) $request->get('sizeXS', false),
            (boolean) $request->get('sizeS', false),
            (boolean) $request->get('sizeM', false),
            (boolean) $request->get('sizeL', false),
            (boolean) $request->get('sizeXL', false)
        );

        $new = ($type == 'new' || (boolean) $request->get('offerNew', false)) ? true : false;
        $sale = ($type == 'sale' || (boolean) $request->get('offerSale', false)) ? true : false;
        $offersArray = array($new, $sale);

        $priceFrom = $request->get('prizeFrom', 0);
        $priceTo = $request->get('prizeTo', $priceMax);
        $orderCategory = $request->get('orderCategory', 'magazine.id');
        $orderDirection = $request->get('orderDirection', 'ASC');


        return new Filter($offersArray, $typesArray, $colorsArray, $sizesArray, $priceFrom, $priceTo, $orderCategory, $orderDirection);
    }

    private function getFilterForm($filter) {
        return $this->createFormBuilder($filter)
            ->add('offers', CollectionType::class, array('entry_type' => CheckboxType::class, 'allow_add' => true))
            ->add('types', CollectionType::class, array('entry_type' => CheckboxType::class, 'allow_add' => true))
            ->add('colors', CollectionType::class, array('entry_type' => CheckboxType::class, 'allow_add' => true))
            ->add('sizes', CollectionType::class, array('entry_type' => CheckboxType::class, 'allow_add' => true))
            ->add('price', RangeType::class, array('attr' => array('min' => 5,'max' => 50)))
            ->getForm();
    }

}