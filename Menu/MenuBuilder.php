<?php
/**
 * @author      Dirk Luijk <dirk@luijkwebcreations.nl>
 * @copyright   Copyright (c) 2014 Dirk Luijk
 * @license     MIT
 */

namespace Serius\Bundle\AdminBundle\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Creates the menu for the admin area, based on
 * the registered dashboard groups from Sonata Admin
 *
 * @package Serius\Bundle\AdminBundle\Menu
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var Pool
     */
    protected $adminPool;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param FactoryInterface      $factory
     * @param Pool                  $adminPool
     * @param TranslatorInterface   $translator
     */
    public function __construct(FactoryInterface $factory, Pool $adminPool, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->adminPool = $adminPool;
        $this->translator = $translator;
    }

    /**
     * Creates the main menu
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('id', 'nav'); // needed for bootstrap

        // Add dashboard first
        $menu->addChild(
            'Dashboard',
            array(
                'route' => 'serius_admin_dashboard',
                'labelAttributes' => array(
                    'class' => 'fa fa-home'     // icon for dashboard
                ),
            )
        );

        // Add Sonata Admin menu items
        foreach ($this->adminPool->getDashboardGroups() as $group) {

            // Do not display the group label if no item in group is available
            $count = 0;

            /**
             * @var $admin \Sonata\AdminBundle\Admin\Admin
             */
            foreach ($group['items'] as $admin) {
                if ($admin->hasRoute('list')) {
                    $count++;
                }
            }

            if ($count == 1) {
                // If one item, add first as menu item
                $admin = $group['items'][0];
                $item = $this->addAdmin($menu, $admin, $request, $group);

                $item->setLabelAttributes(array(
                    'class' => $group['icon'] ?: 'fa fa-folder',
                ));
            } elseif ($count > 1) {
                // If more items, add as submenu item
                $groupItem = $menu->addChild($group['label'], array(
                    'uri' => '#',
                    'labelAttributes' => array(
                        'class' => $group['icon'] ?: 'fa fa-folder',
                    ),
                ));

                foreach ($group['items'] as $admin) {
                    $this->addAdmin($groupItem, $admin, $request, $group);
                }
            }
        }

        return $menu;
    }

    private function addAdmin(ItemInterface $parentItem, AdminInterface $admin, Request $request, $groupConfig)
    {
        $label = $admin->getLabel();

        if ($groupConfig['label_catalogue']) {
            $label = $this->translator->trans($label, array(), $groupConfig['label_catalogue']);
        }

        $adminItem = $parentItem->addChild($label, array(
            'uri' => $admin->generateUrl('list'),
            'labelAttributes' => array(
                'class' => 'fa fa-angle-double-right'
            ),
        ));

        if (strpos($request->get('_sonata_admin'), $admin->getCode()) === 0) {
            $adminItem->setCurrent(true);
        }

        return $adminItem;
    }
}
