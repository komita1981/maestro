<?php

/*
* This file is part of the Sider package.
*
* (c) Milan Popovic - <komita1981@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Komita\SiderBundle\EventListener;

use Komita\SiderBundle\Controller\CoreController;
use Komita\SiderBundle\Controller\KeyController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Komita\SiderBundle\Form\Search;

class SearchFormListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if (! $controller[0] instanceof CoreController and ! $controller[0] instanceof KeyController) {
            return;
        }
        $search_form = $controller[0]->createForm(new Search(), null, array(
            'action' => $controller[0]->generateUrl('komita_sider_browse_keys'),
        ));

        if (! empty($_GET)){
            $search_form->submit($controller[0]->getRequest());
        }

        $controller[0]->setSearchForm($search_form)->addResponseData('search_form', $search_form->createView());
    }
}