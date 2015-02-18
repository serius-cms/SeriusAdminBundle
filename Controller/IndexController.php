<?php
/**
 * @author      Dirk Luijk <dirk@luijkwebcreations.nl>
 * @copyright   Copyright (c) 2014 Dirk Luijk
 * @license     MIT
 */

namespace Serius\Bundle\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class IndexController
 * @package Serius\Bundle\CmsBundle\Controller
 * @Route(service="serius_admin.controller.index")
 */
class IndexController
{
    /**
     * @Route("/")
     * @Route("/", name="serius_admin_dashboard")
     * @Template
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
} 