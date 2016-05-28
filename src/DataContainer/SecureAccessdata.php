<?php

/*
 * This file is part of the Secure Accessdata Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\SecureAccessdataBundle\DataContainer;

use Contao\Backend;
use Contao\Database;
use Contao\DataContainer;
use Contao\Encryption;
use Contao\Input;
use Contao\UserModel;

class SecureAccessdata extends Backend
{
    public function __construct()
    {
        parent::__construct();

        // Import
        $this->import('BackendUser', 'User');
    }

    public function checkAccess()
    {
        $this->import('Input');

        // If no access
        if (Input::get('id') != '' && Input::get('id') != null && in_array(Input::get('id'),
                $this->filterFields(true))
        ) {
            $this->log('No access on secure access data ID "' . Input::get('id') . '"',
                'tl_secure_accessdata __construct', TL_ERROR);
            $this->redirect('main.php?act=error');
        }
    }

    public function setFilter()
    {
        $arrFilter = $this->filterFields();
        $GLOBALS['TL_DCA']['tl_secure_accessdata']['list']['sorting']['filter'] = $arrFilter;
    }

    /**
     * @param bool $isOnlyIDs
     * @return array
     */
    public function filterFields($isOnlyIDs = false)
    {
        $arrClosedEntries = array();

        // Get all secure accessdata results
        $objSecureAccessdata = Database::getInstance()->prepare("SELECT * FROM tl_secure_accessdata")->execute();

        if ($objSecureAccessdata !== null) {
            while ($objSecureAccessdata->next()) {
                $id = $objSecureAccessdata->id;
                $author = $objSecureAccessdata->author;
                $protect = Encryption::decrypt($objSecureAccessdata->protect);
                $protect_users = Encryption::decrypt(deserialize($objSecureAccessdata->protect_users));
                $protect_groups = Encryption::decrypt(deserialize($objSecureAccessdata->protect_groups));

                // If protected
                if ($protect == 1) {
                    // If not admin
                    if ($this->User->admin != 1) {
                        // If not author
                        if ($this->User->id != $author) {
                            // If not in user array
                            if (!(is_array($protect_users) && in_array($this->User->id, $protect_users))) {
                                // If not in group array
                                if (!(is_array($this->User->groups) && is_array($protect_groups)
                                    && count(array_intersect($this->User->groups, $protect_groups)) > 0)
                                ) {
                                    if ($isOnlyIDs == true) {
                                        $arrClosedEntries[] = $id;
                                    } else {
                                        $arrClosedEntries[] = array('id!=?', $id);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $arrClosedEntries;
    }

    /**
     * @param $row
     * @param $label
     * @param DataContainer $dc
     * @param $args
     * @return mixed
     */
    public function labelCallback($row, $label, DataContainer $dc, $args)
    {
        // Set image
        $image = 'protect';

        if (Encryption::decrypt($row['protect']) == '1') {
            $image .= '_';
        }

        $args[0] = sprintf(
            '<div class="list_icon_new" style="background-image:url(\'%s%s/images/%s.gif\')">&nbsp;</div>',
            TL_SCRIPT_URL,
            SECURE_ACCESSDATA_PATH,
            $image
        );

        // Set User
        if (is_numeric($args[3])) {
            $objUser = UserModel::findByPk($args[3]);

            if ($objUser !== null) {
                $args[3] = $objUser->name;
            }
        }

        switch ($row['type']) {
            case 'weblogin':
                $args[4] = sprintf('%s<br>%s',
                    Encryption::decrypt($row['weblogin_name']),
                    Encryption::decrypt($row['weblogin_pwd'])
                );
                break;

            case 'contao_login':
                $args[4] = sprintf('%s<br>%s',
                    Encryption::decrypt($row['contao_user']),
                    Encryption::decrypt($row['contao_pwd'])
                );
                break;

            case 'encryption_key':
                $strEncryptionKey = Encryption::decrypt($row['encryption_key']);

                if (strlen($strEncryptionKey) <= 32) {
                    $args[4] = $strEncryptionKey;
                } else {
                    $args[4] = substr($strEncryptionKey, 0, 29) . '...';
                }
                break;

            case 'mail':
                $args[4] = sprintf('%s<br>%s<br>%s',
                    Encryption::decrypt($row['mail_email']),
                    Encryption::decrypt($row['mail_loginname']),
                    Encryption::decrypt($row['mail_pwd'])
                );
                break;

            case 'project':
                break;

            case 'online_project':
                break;
        }

        return $args;
    }
}
