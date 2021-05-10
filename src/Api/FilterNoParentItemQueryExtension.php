<?php


namespace App\Api;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Item;
use Doctrine\ORM\QueryBuilder;

class FilterNoParentItemQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (Item::class === $resourceClass) {
            $queryBuilder->andWhere(sprintf("%s.parent IS NULL",
                $queryBuilder->getRootAliases()[0]));
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        if (Item::class === $resourceClass) {
            $queryBuilder->andWhere(sprintf("%s.parent IS NOT NULL",
                $queryBuilder->getRootAliases()[0]));
        }
    }
}