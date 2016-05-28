<?php

/*
 * This file is part of the Secure Accessdata Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('SECURE_ACCESSDATA_PATH', 'bundles/craffftsecureaccessdata');

/**
 * Backend Modules
 */
$GLOBALS['BE_MOD']['system']['tl_secure_accessdata'] = array
(
    'tables' => array('tl_secure_accessdata'),
    'icon'   => SECURE_ACCESSDATA_PATH . '/images/protect_.gif'
);

/**
 * Secure Accessdata types
 */
$GLOBALS['TL_SADTY'] = array
(
    'weblogin'       => 'weblogin',
    'contao_login'   => 'contao_login',
    'encryption_key' => 'encryption_key',
    'mail'           => 'mail',
    'project'        => 'project',
    'online_project' => 'online_project'
);

/**
 * Secure Accessdata FTP protocols
 */
$GLOBALS['TL_SAD_FTP_PROTOCOL'] = array
(
    'ftp'              => 'ftp',
    'sftp'             => 'sftp',
    'ftp_ssl_implicit' => 'ftp_ssl_implicit',
    'ftp_tls_ssl'      => 'ftp_tls_ssl',
    'webdav'           => 'webdav',
    'webdav_https'     => 'webdav_https'
);

/**
 * Secure Accessdata mail crypt
 */
$GLOBALS['TL_SAD_MAIL_CRYPT'] = array
(
    'ssl' => 'SSL',
    'tls' => 'TLS'
);
