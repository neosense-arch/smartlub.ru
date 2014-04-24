<?php

namespace SL\FrontBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use NS\CatalogBundle\Entity\Category;
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
class BlocksController extends Controller
{
    /**
     * @param Request                   $request
     * @param Block                     $block
     * @param FiltersBlockSettingsModel $settings
     * @return Response
     * @throws \Exception
     */
    public function filtersBlockAction(Request $request, Block $block, FiltersBlockSettingsModel $settings)
    {
        if (!$settings->getVendorCategoryId()) {
            throw new \Exception("Vendor category id wasn't set");
        }

        /** @var FiltersService $filtersService */
        $filtersService = $this->get('sl_front.service.filters');
        $filters = $filtersService->getFilters();
        if (!$filters) {
            $filters = new Filters();
            $filters->setPriceFrom($settings->getPriceFrom());
            $filters->setPriceTo($settings->getPriceTo());
        }

        $formType = new FiltersType($settings->getVendorCategoryId());
        $form = $this->createForm($formType, $filters);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $filtersService->updateFilters($filters);
        }

        return $this->render($block->getTemplate(), array(
            'block'    => $block,
            'settings' => $settings,
            'form'     => $form->createView(),
        ));
    }

    /**
     * @param Block                   $block
     * @param ItemsBlockSettingsModel $settings
     * @param string                  $categorySlug
     * @return Response
     */
    public function itemsBlockAction(Block $block, ItemsBlockSettingsModel $settings, $categorySlug = null)
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

        $total = count($items);

        // limit (doctrine magic)
        if ($settings->getLimit()) {
            $items = array_slice($items, 0, $settings->getLimit());
        }

        return $this->render($block->getTemplate(), array(
            'block'        => $block,
            'items'        => $items,
            'total'        => $total,
            'categorySlug' => $categorySlug,
        ));
    }
}
