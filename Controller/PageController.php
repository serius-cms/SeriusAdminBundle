<?php
/**
 * @author      Dirk Luijk <dirk@luijkwebcreations.nl>
 * @copyright   Copyright (c) 2014 Dirk Luijk
 * @license     MIT
 */

namespace Serius\Bundle\AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Serius\Bundle\PageBundle\Entity\Page;
use Serius\Bundle\PageBundle\Repository\PageRepository;

/**
 * Class PageController
 * @package Serius\Bundle\CmsBundle\Controller
 * @Route("/pages", service="serius_admin.controller.page")
 */
class PageController
{
    /**
     * @var PageRepository
     */
    protected $pageRepository;

    /**
     * Constructor
     *
     * @param PageRepository $pageRepository
     */
    function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return array
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {
        $data = $this->createTreeData();

        return array(
            'treeData' => $data,
        );
    }

    /**
     * @param \Serius\Bundle\PageBundle\Entity\Page $page
     * @return array
     * @Route("/edit/{id}", options={"expose"=true})
     * @Template
     */
    public function editAction(Page $page)
    {
        $data = $this->createTreeData($page);

        return array(
            'page' => $page,
            'treeData' => $data,
        );
    }

    /**
     * Creates a node array of a page
     *
     * @param Page $page
     * @param \Serius\Bundle\PageBundle\Entity\Page $selectedPage
     * @return array
     */
    private function createNodeArray(Page $page, Page $selectedPage = null)
    {
        $type = $page->getStringType();

        $node = array(
            'id' => $page->getId(),
            'text' => $page->getName(),
            'enabled' => $page->isEnabled(),
            'visible' => $page->isVisible(),
            'trash' => false,
            'redirect' => $page->isRedirect(),
            'type' =>  $type,
        );

        if($selectedPage && $selectedPage == $page) {
            $node['state']['selected'] = true;
        }

        if($page->getChildren()->isEmpty()) {
            $node['children'] = false;
        }
        else {
            $node['children'] = array();

            foreach($page->getChildren() as $childPage) {
                $node['children'][] = $this->createNodeArray($childPage, $selectedPage);
            }
        }

        return $node;
    }

    private function createTreeData(Page $selectedPage = null)
    {
        $pages = $this->pageRepository->getRootPages();
        $data = array();

        foreach($pages as $child) {
            $data[] = $this->createNodeArray($child, $selectedPage);
        }

        return $data;
    }
} 