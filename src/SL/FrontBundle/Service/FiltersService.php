<?php

namespace SL\FrontBundle\Service;

use NS\AdminBundle\Form\DataTransformer\EntityToIdTransformer;
use NS\CatalogBundle\Entity\ItemRepository;
use SL\FrontBundle\Model\Filters;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FiltersService
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @param SessionInterface $session
     * @param ItemRepository   $itemRepository
     */
    public function __construct(SessionInterface $session, ItemRepository $itemRepository)
    {
        $this->session        = $session;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @return Filters
     */
    public function getFilters()
    {
        $serialized = $this->session->get('sl_front_filters');
        if (!$serialized) {
            return null;
        }
        return $this->unserializeFilters($serialized);
    }

    /**
     * @param Filters $filters
     */
    public function updateFilters(Filters $filters)
    {
        $this->session->set('sl_front_filters', $this->serializeFilters($filters));
    }

    /**
     * @param Filters $filters
     * @return string
     */
    private function serializeFilters(Filters $filters)
    {
        $trans = new EntityToIdTransformer($this->itemRepository);

        $raw = array(
            'vendorId'  => $trans->transform($filters->getVendor()),
            'priceFrom' => $filters->getPriceFrom(),
            'priceTo'   => $filters->getPriceTo(),
        );

        return json_encode($raw);
    }

    /**
     * @param $serialized
     * @return Filters
     */
    private function unserializeFilters($serialized)
    {
        $trans = new EntityToIdTransformer($this->itemRepository);

        $raw = array_merge(
            array(
                'vendorId'  => null,
                'priceFrom' => null,
                'priceTo'   => null,
            ),
            json_decode($serialized, true)
        );

        $filters = new Filters();
        $filters->setVendor($trans->reverseTransform($raw['vendorId']));
        $filters->setPriceFrom($raw['priceFrom']);
        $filters->setPriceTo($raw['priceTo']);

        return $filters;
    }
} 