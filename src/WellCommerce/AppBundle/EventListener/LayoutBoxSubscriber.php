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
namespace WellCommerce\AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use WellCommerce\CoreBundle\Event\ResourceEvent;
use WellCommerce\AppBundle\EventListener\AbstractEventSubscriber;
use WellCommerce\CoreBundle\Event\FormEvent;
use WellCommerce\AppBundle\Configurator\LayoutBoxConfiguratorInterface;

/**
 * Class LayoutBoxSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LayoutBoxSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            'layout_box.form_init'  => 'onLayoutBoxFormInit',
            'layout_box.pre_update' => 'onLayoutBoxResourceSave'
        ];
    }

    /**
     * Adds configurator fields to main layout box edit form.
     * Loops through all configurators, renders the fieldset and sets default data
     *
     * @param FormEvent $event
     */
    public function onLayoutBoxFormInit(FormEvent $event)
    {
        $builder       = $event->getFormBuilder();
        $form          = $event->getForm();
        $resource      = $form->getModelData();
        $configurators = $this->container->get('layout_box.configurator.collection')->all();
        $boxSettings   = $resource->getSettings();

        foreach ($configurators as $configurator) {
            if ($configurator instanceof LayoutBoxConfiguratorInterface) {
                $defaults = [];
                if ($resource->getBoxType() == $configurator->getType()) {
                    $defaults = $boxSettings;
                }

                $configurator->addFormFields($builder, $form, $defaults);
            }
        }
    }

    /**
     * Sets resource settings fetched from fieldset corresponding to selected box type
     *
     * @param ResourceEvent $event
     */
    public function onLayoutBoxResourceSave(ResourceEvent $event)
    {
        $settings = $this->getBoxSettingsFromRequest($event->getRequest());
        $resource = $event->getResource();
        $resource->setSettings($settings);
    }

    protected function getBoxSettingsFromRequest(Request $request)
    {
        $settings   = [];
        $accessor   = PropertyAccess::createPropertyAccessor();
        $parameters = $request->request->all();
        $boxType    = $accessor->getValue($parameters, '[required_data][boxType]');
        if ($accessor->isReadable($parameters, '[' . $boxType . ']')) {
            $settings = $accessor->getValue($parameters, '[' . $boxType . ']');
        }

        return !is_array($settings) ? [] : $settings;
    }
}