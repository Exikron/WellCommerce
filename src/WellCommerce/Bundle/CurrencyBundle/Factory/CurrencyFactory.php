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

namespace WellCommerce\Bundle\CurrencyBundle\Factory;

use WellCommerce\Bundle\CurrencyBundle\Entity\Currency;
use WellCommerce\Bundle\CoreBundle\Factory\AbstractFactory;

/**
 * Class CurrencyFactory
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencyFactory extends AbstractFactory
{
    /**
     * @return \WellCommerce\Bundle\CurrencyBundle\Entity\CurrencyInterface
     */
    public function create()
    {
        $currency = new Currency();
        $currency->setCode('');

        return $currency;
    }
}