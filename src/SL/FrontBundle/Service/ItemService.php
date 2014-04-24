<?php

namespace SL\FrontBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use NS\CatalogBundle\Entity\Category;
use NS\CatalogBundle\Entity\Item;
use SL\FrontBundle\Model\Filters;

class ItemService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param Filters  $filters
     * @param Category $category
     * @return Item[]
     */
    public function getItems(Filters $filters, Category $category = null)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->em->createQueryBuilder();

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

        // category condition
        if ($category) {
            // using subcategories
            $ids = $this->getCategoryIds($category);
            $queryBuilder
                ->andWhere('c.id IN (:ids)')
                ->setParameter('ids', $ids);
        }

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

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param Category $category
     * @return array
     */
    private function getCategoryIds(Category $category)
    {
        $ids = array($category->getId());
        foreach ($category->getChildren() as $subCategory) {
            $ids = array_merge($ids, $this->getCategoryIds($subCategory));
        }
        return $ids;
    }
} 