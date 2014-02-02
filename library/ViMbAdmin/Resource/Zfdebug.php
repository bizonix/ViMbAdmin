<?php

/**
 * Open Solutions' ViMbAdmin Project.
 *
 * This file is part of Open Solutions' ViMbAdmin Project which is a
 * project which provides an easily manageable web based virtual
 * mailbox administration system.
 *
 * Copyright (c) 2011 Open Source Solutions Limited
 *
 * ViMbAdmin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ViMbAdmin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ViMbAdmin.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Open Source Solutions Limited T/A Open Solutions
 *   147 Stepaside Park, Stepaside, Dublin 18, Ireland.
 *   Barry O'Donovan <barry _at_ opensolutions.ie>
 *
 * @copyright Copyright (c) 2011 Open Source Solutions Limited
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3 (GPLv3)
 * @author Open Source Solutions Limited <info _at_ opensolutions.ie>
 * @author Barry O'Donovan <barry _at_ opensolutions.ie>
 * @author Roland Huszti <roland _at_ opensolutions.ie>
 */

/*
 * The Zend zfdebug resource.
 *
 * @package ViMbAdmin
 * @subpackage Resource
 */
class ViMbAdmin_Resource_Zfdebug extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Holds the ZFDebug Instance
     * 
     * @var null|ZFDebug_Controller_Plugin_Debug
     */
    protected $_zfdebug;

    /**
     * Initialisation function
     * 
     * @return ZFDebug_Controller_Plugin_Debug
     */
    public function init()
    {
        // Return session so bootstrap will store it in the registry
        return $this->getZfdebug();
    }


    /**
     * Get Zfdebug
     * 
     * @return ZFDebug_Controller_Plugin_Debug
     */
    public function getZfdebug()
    {
        if( null === $this->_zfdebug ) 
        {
            $this->getBootstrap()->bootstrap( 'Session' );

            // Get Zfdebug configuration options from the application.ini file
            $zfdebugConfig = $this->getOptions();

            if( $zfdebugConfig['enabled'] )
            {
                $autoloader = Zend_Loader_Autoloader::getInstance();
                $autoloader->registerNamespace('ZFDebug');

                $options = array(
                    'plugins' => $zfdebugConfig['plugins']
                );

                # Instantiate the database adapter and setup the plugin.
                # Alternatively just add the plugin like above and rely on the autodiscovery feature.
                if( $this->getBootstrap()->hasPluginResource( 'db' ) ) 
                {
                    $this->getBootstrap()->bootstrap('db');

                    $db = $this->getBootstrap()->getPluginResource( 'db' )->getDbAdapter();
                    $options['plugins']['Database']['adapter'] = $db;
                }

                # Setup the cache plugin
                if( $this->getBootstrap()->hasPluginResource( 'cache' ) ) 
                {
                    $this->getBootstrap()->bootstrap( 'cache' );
                    $cache = $this->getBootstrap()->getPluginResource( 'cache' )->getDbAdapter();
                    $options['plugins']['Cache']['backend'] = $cache->getBackend();
                }

                $this->getBootstrap()->bootstrap( 'Doctrine' );
                $options['plugins']['ViMbAdmin_ZFDebug_Controller_Plugin_Debug_Plugin_Doctrine']['manager'] 
                    = $this->getBootstrap()->getResource( 'Doctrine' );

                $this->_zfdebug = new ZFDebug_Controller_Plugin_Debug( $options );

                $this->getBootstrap()->bootstrap( 'FrontController' );
                $frontController = $this->getBootstrap()->getResource('FrontController');
                $frontController->registerPlugin($this->_zfdebug);
            }        
        }

        return $this->_zfdebug;
    }    


} 
