<?php

/*
* This file is part of the Sider package.
*
* (c) Milan Popovic - <komita1981@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Komita\SiderBundle\Controller;

/**
 * Dashboard controller
 *
 * Class DashboardController
 * @package Komita\SiderBundle\Controller
 */
class DashboardController extends CoreController
{
    public function displayAction()
    {
        return $this->render('KomitaSiderBundle:Dashboard:display.html.twig', $this->response_data);
    }
}
