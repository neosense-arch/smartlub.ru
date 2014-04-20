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
     * @return Response
     */
    public function itemsBlockAction(Block $block, ItemsBlockSettingsModel $settings)
    {
        /** @var FiltersService $filtersService */
        $filtersService = $this->get('sl_front.service.filters');
        $filters = $filtersService->getFilters();
        if (!$filters) {
            $filters = new Filters();
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();

        // building query
        $queryBuilder
            ->select('i', 'c', 's')
            ->from('NSCatalogBundle:Item', 'i')
            ->join('i.category', 'c')
            ->join('i.rawSettings', 's')

            // visible
            ->andWhere('i.visible = :visible')
            ->setParameter('visible', true)
        ;

        // vendor
        if ($filters->getVendor()) {
            $queryBuilder
                ->join('i.rawSettings', "s1", 'WITH', "s1.name = :name1 AND s1.value IN (:value1)")
                ->setParameter("name1", 'vendor')
                ->setParameter("value1", $filters->getVendor()->getId());
        }

        // price
        if ($filters->getPriceFrom()) {
            $queryBuilder
                ->join('i.rawSettings', "s2", 'WITH', "s2.name = :name2 AND s2.value >= :value2")
                ->setParameter("name2", 'price')
                ->setParameter("value2", (int)$filters->getPriceFrom());
        }
        if ($filters->getPriceTo()) {
            $queryBuilder
                ->join('i.rawSettings', "s3", 'WITH', "s3.name = :name3 AND s3.value <= :value3")
                ->setParameter("name3", 'price')
                ->setParameter("value3", (int)$filters->getPriceTo());
        }

        // ordering
        // adding order expression
        $queryBuilder
            ->join('i.rawSettings', 'sOrder', 'WITH', "sOrder.name = :sOrderName")
            ->setParameter("sOrderName", 'price')
            ->addOrderBy('sOrder.value + 0', 'ASC');

        $items = $queryBuilder->getQuery()->getResult();
        $total = count($items);

        // limit (doctrine magic)
        if ($settings->getLimit()) {
            $items = array_slice($items, 0, $settings->getLimit());
        }

        return $this->render($block->getTemplate(), array(
            'block' => $block,
            'items' => $items,
            'total' => $total,
        ));
    }
}
