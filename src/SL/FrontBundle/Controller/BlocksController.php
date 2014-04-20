<?php

namespace SL\FrontBundle\Controller;

use NS\CmsBundle\Entity\Block;
use SL\FrontBundle\Block\Settings\FiltersBlockSettingsModel;
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
}
