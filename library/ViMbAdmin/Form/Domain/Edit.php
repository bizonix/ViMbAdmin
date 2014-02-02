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

/**
 * The form for adding and editing domains.
 *
 * @package ViMbAdmin
 * @subpackage Form
 */
class ViMbAdmin_Form_Domain_Edit extends ViMbAdmin_Form
{

    public function __construct( $options = null )
    {
        parent::__construct( $options );

        $this->setDecorators( array( array( 'ViewScript', array( 'viewScript' => 'domain/form/edit.phtml' ) ) ) );

        $this
            ->setMethod( 'post' )
            ->setAttrib( 'id', 'domain_edit_form' )
            ->setAttrib( 'name', 'domain_edit_form' );

        $domain = $this
                        ->createElement( 'text', 'domain' )
                        ->setAttrib( 'size', 40 )
                        ->setLabel( _( 'Domain' ) )
                        ->setAttrib( 'title', _( 'Domain' ) )
                        ->setAttrib( 'class', 'required' )
                        ->setRequired( true )
                        ->addValidator( 'NotEmpty', true )
                        ->addValidator( 'Hostname', true, array( 'allow' => (Zend_Validate_Hostname::ALLOW_DNS | Zend_Validate_Hostname::ALLOW_LOCAL) ) )
                        ->addValidator( 'DoctrineUniqueness', true, array( 'table' => 'Domain', 'column' => 'domain' ) )
                        ->addFilter( 'StringTrim' )
                        ->addFilter( 'HtmlEntitiesDecode' )
                        ->addFilter( 'StringToLower' )
                        ->addFilter( 'StripSlashes' );

        $description = $this
                        ->createElement( 'textarea', 'description' )
                        ->setLabel( _( 'Description' ) )
                        ->setAttrib( 'title', _( 'Description' ) )
                        ->setAttrib( 'rows', 2 )
                        ->setAttrib( 'cols', 60 )
                        ->setRequired( false )
                        ->addValidator( 'StringLength', true, array( 0, 255 ) )
                        ->addFilter( 'HtmlEntitiesDecode' )
                        ->addFilter( 'StripSlashes' );

        $backupMx = $this
                        ->createElement( 'checkbox', 'backupmx' )
                        ->setLabel( _( 'Backup MX' ) )
                        ->addValidator( 'InArray', false, array( array( 0, 1 ) ) )
                        ->addFilter( 'Digits' );

        $active = $this
                        ->createElement( 'checkbox', 'active' )
                        ->setLabel( _( 'Active' ) )
                        ->addValidator( 'InArray', false, array( array( 0, 1 ) ) )
                        ->addFilter( 'Digits' );

        $aliases = $this
                        ->createElement( 'text', 'aliases' )
                        ->setLabel( _( 'Aliases' ) )
                        ->setAttrib( 'title', _( 'Aliases' ) )
                        ->setAttrib( 'size', 5 )
                        ->setRequired( false )
                        ->addFilter( 'Digits' );

        $mailboxes = $this
                        ->createElement( 'text', 'mailboxes' )
                        ->setLabel( _( 'Mailboxes' ) )
                        ->setAttrib( 'title', _( 'Mailboxes' ) )
                        ->setAttrib( 'size', 5 )
                        ->setRequired( false )
                        ->addFilter( 'Digits' );

        $maxQuota = $this
                        ->createElement( 'text', 'maxquota' )
                        ->setLabel( _( 'Max Quota' ) )
                        ->setAttrib( 'title', _( 'Max Quota' ) )
                        ->setAttrib( 'size', 5 )
                        ->setRequired( false )
                        ->addFilter( 'Digits' );

        $quota = $this
                        ->createElement( 'text', 'quota' )
                        ->setLabel( _( 'Quota' ) )
                        ->setAttrib( 'title', _( 'Quota' ) )
                        ->setAttrib( 'size', 5 )
                        ->setRequired( false )
                        ->addFilter( 'Digits' );

        $transport = $this
                        ->createElement( 'text', 'transport' )
                        ->setAttrib( 'size', 40 )
                        ->setLabel( _( 'Transport' ) )
                        ->setAttrib( 'title', _( 'Transport' ) )
                        ->setRequired( false )
                        ->addValidator( 'StringLength', false, array( 0, 255 ) )
                        ->addFilter( 'StringTrim' )
                        ->addFilter( 'HtmlEntitiesDecode' )
                        ->addFilter( 'StripSlashes' );

        $submit = $this
                        ->createElement( 'submit' , 'save' )
                        ->setLabel( _( 'Save' ) );

        $this
            ->addElement( $domain )
            ->addElement( $description )
            ->addElement( $backupMx )
            ->addElement( $active )
            ->addElement( $aliases )
            ->addElement( $mailboxes )
            ->addElement( $maxQuota )
            ->addElement( $quota )
            ->addElement( $transport )
            ->addElement( $submit );

        $this->setElementDecorators( array( 'ViewHelper' ) );
    }

}
