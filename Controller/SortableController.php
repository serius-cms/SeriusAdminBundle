<?php
/**
 * @author      Dirk Luijk <dirk@luijkwebcreations.nl>
 * @copyright   Copyright (c) 2014 Dirk Luijk
 * @license     MIT
 */

namespace Serius\Bundle\AdminBundle\Controller;


use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SortableController extends CRUDController
{
    public function sortAction(Request $request)
    {
        $sorted = $request->query->get('row');

        // retreive lists of objects
        $query = $this->admin->createQuery();
        $objects = $query->execute();

        foreach ($objects as $object) {
            // find new position
            $position = array_search($this->admin->id($object), $sorted);

            // assume setPosition?
            $object->setPosition($position);
            $this->admin->update($object);
        }

        return new JsonResponse(array(
            'success'
        ));
    }
} 