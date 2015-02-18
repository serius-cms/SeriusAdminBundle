<?php
/**
 * @author      Dirk Luijk <dirk@luijkwebcreations.nl>
 * @copyright   Copyright (c) 2014 Dirk Luijk
 * @license     MIT
 */

namespace Serius\Bundle\AdminBundle\Controller;


use Doctrine\ORM\EntityManager;
use Serius\Bundle\PageBundle\Entity\Page;
use Serius\Bundle\PageBundle\Repository\PageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class PageTreeController
 * @package Serius\Bundle\AdminBundle\Controller
 * @Route("/pages/tree", service="serius_admin.controller.page_tree")
 */
class PageTreeController
{
    /**
     * @var PageRepository
     */
    protected $pageRepository;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * Constructor
     * @param PageRepository $pageRepository
     * @param \Doctrine\ORM\EntityManager $manager
     */
    function __construct(PageRepository $pageRepository, EntityManager $manager)
    {
        $this->pageRepository = $pageRepository;
        $this->manager = $manager;
    }

    /**
     * @param Page $page
     * @param Request $request
     * @return JsonResponse
     * @Route("/{id}/add", name="serius_admin_page_tree_add_child", options={"expose"=true})
     */
    public function addChildAction(Page $page, Request $request)
    {
        $name = $request->get('name');
        $newPage = new Page($name, $page);

        $newPage->setVisible(false);
        $newPage->setEnabled(false);

        $this->manager->persist($newPage);
        $this->manager->flush();

        return new JsonResponse(array(
            'id' => $newPage->getId(),
        ));
    }

    /**
     * @param Page $page
     * @param Request $request
     * @return JsonResponse
     * @Route("/{id}/rename", name="serius_admin_page_tree_rename", options={"expose"=true})
     */
    public function renameAction(Page $page, Request $request)
    {
        $newName = $request->get('name');
        $page->setName($newName);

        $this->manager->persist($page);
        $this->manager->flush();

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * @param Page $page
     * @param Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return JsonResponse
     * @Route("/{id}/move", name="serius_admin_page_tree_move", options={"expose"=true})
     */
    public function moveAction(Page $page, Request $request)
    {
        $parent_id = $request->get('parent_id');
        $position = (int) $request->get('position') ?: 0;

        $parent = $this->pageRepository->find($parent_id);

        if(!$parent)
            $parent = $this->pageRepository->getRootPage();

        $page->setParent($parent);
        $this->manager->persist($page);
        $this->manager->flush();

        $page->setPosition($position);
        $this->manager->persist($page);
        $this->manager->flush();

        //$repo->reorder($parent, 'position');
        //$repo->reorderAll('position');

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * @param Page $page
     * @return JsonResponse
     * @Route("/{id}/delete", name="serius_admin_page_tree_delete", options={"expose"=true})
     */
    public function deleteAction(Page $page)
    {
        $this->manager->remove($page);
        $this->manager->flush();

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * @param Page $page
     * @return JsonResponse
     * @Route("/{id}/show", name="serius_admin_page_tree_show", options={"expose"=true})
     */
    public function showAction(Page $page)
    {
        $page->setVisible(true);
        $this->manager->persist($page);
        $this->manager->flush();

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * @param Page $page
     * @return JsonResponse
     * @Route("/{id}/hide", name="serius_admin_page_tree_hide", options={"expose"=true})
     */
    public function hideAction(Page $page)
    {
        $page->setVisible(false);
        $this->manager->persist($page);
        $this->manager->flush();

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * @param Page $page
     * @return JsonResponse
     * @Route("/{id}/disable", name="serius_admin_page_tree_disable", options={"expose"=true})
     */
    public function disableAction(Page $page)
    {
        $page->setEnabled(false);
        $this->manager->persist($page);
        $this->manager->flush();

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * @param Page $page
     * @return JsonResponse
     * @Route("/{id}/enable", name="serius_admin_page_tree_enable", options={"expose"=true})
     */
    public function enableAction(Page $page)
    {
        $page->setEnabled(true);
        $this->manager->persist($page);
        $this->manager->flush();

        return new JsonResponse(array(
            'success' => true
        ));
    }
} 