<?php

namespace SL\FrontBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use NS\CatalogBundle\Service\CatalogService;
use NS\CmsBundle\Entity\Block;
use SL\FrontBundle\Block\Settings\FiltersBlockSettingsModel;
use SL\FrontBundle\Block\Settings\ItemsBlockSettingsModel;
use SL\FrontBundle\Form\Type\FiltersType;
use SL\FrontBundle\Model\Filters;
use SL\FrontBundle\Service\FiltersService;
use SL\FrontBundle\Service\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BlocksController
 *
 * @package SL\FrontBundle\Controller
 */
class ItemsController extends Controller
{
    public function loadMoreAction(Request $request, $categorySlug = null)
    {
        /** @var FiltersService $filtersService */
        $filtersService = $this->get('sl_front.service.filters');
        $filters = $filtersService->getFilters();
        if (!$filters) {
            $filters = new Filters();
        }

        // category condition
        $category = null;
        if ($categorySlug) {
            /** @var CatalogService $catalogService */
            $catalogService = $this->get('ns_catalog_service');
            $category = $catalogService->getCategoryBySlug($categorySlug);
            if (!$category) {
                return new Response('', 404);
            }
        }

        /** @var ItemService $itemService */
        $itemService = $this->get('sl_front.service.item');
        $items = $itemService->getItems($filters, $category);

        // limit (doctrine magic)
        $items = array_slice($items, $request->query->get('skip'), $request->query->get('limit'));

        return $this->render('SLFrontBundle:Items:loadMore.html.twig', array(
            'items' => $items,
        ));
    }
}
