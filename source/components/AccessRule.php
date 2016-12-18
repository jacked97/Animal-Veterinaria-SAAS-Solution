<?php

namespace app\components;

use app\models\User;

class AccessRule extends \yii\filters\AccessRule
{

    /**
     * @inheritdoc
     */
    protected function matchRole($user)
    {
//        print_r($user);
//        print_r($this->roles);

//        print_r();
        if (empty($this->roles)) {
            return true;
        }
//        HelperFunctions::outputFormatted($user->identity->type);
        foreach ($this->roles as $role) {
//            print_r($role);
            if ($role == '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif (!$user->getIsGuest() && $role == $user->identity->type) {
                return true;
            }
        }

        return false;
    }

}
