<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CatalogBundle\Tests\DataSet\Front;

use WellCommerce\Bundle\CoreBundle\Test\DataSet\AbstractDataSetTestCase;

/**
 * Class CategoryDataSetTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryDataSetTest extends AbstractDataSetTestCase
{
    protected function get()
    {
        return $this->container->get('category.dataset.front');
    }

    protected function getColumns()
    {
        return [
            'id'             => 'category.id',
            'hierarchy'      => 'category.hierarchy',
            'enabled'        => 'category.enabled',
            'parent'         => 'IDENTITY(category.parent)',
            'children_count' => 'category.childrenCount',
            'products_count' => 'category.productsCount',
            'name'           => 'category_translation.name',
            'slug'           => 'category_translation.slug',
            'shop'           => 'category_shops.id',
            'route'          => 'IDENTITY(category_translation.route)',
        ];
    }
}
