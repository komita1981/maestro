<?php

/*
* This file is part of the Sider package.
*
* (c) Milan Popovic - <komita1981@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Komita\SiderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Search keys form
 *
 * Class Search
 * @package Komita\SiderBundle\Form
 */
class Search extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
        ->setMethod('GET')
        ->setAction($options['action'])
        ->add('key', 'text', array('label' => 'Key name',
                                   'required' => false))
        ->add('datatype', 'choice', array(
                'label' => 'Data Type',
                'choices'   => array('string' => 'String',
                                     'set' => 'Set',
                                     'zset' => 'Sorted Set',
                                     'hash' => 'Hash',
                                     'list' => 'List'),
                'required'  => false,
            ))
        ->add('database', 'choice', array(
                'label' => 'Data Type',
                'choices'   => array('0'     => '0',
                                     '1' => '1',
                                     '2' => '2',
                                     '3' => '3',
                                     '4' => '4',
                                     '5' => '5',
                                     '6' => '6',
                                     '7' => '7',
                                     '8' => '8',
                                     '9' => '9',
                                     '10' => '10',
                                     '11' => '11',
                                     '12' => '12',
                                     '13' => '13',
                                     '14' => '14',
                                     '15' => '15',
                ),
                'data' => '0',
                'required'  => false,
            ))
        ->add('search', 'submit');
    }
    public function getName()
    {
        return 'search_keys';
    }
}
