<?php


namespace App\Entities;


use App\Libraries\IonAuth;
use App\Models\IonAuthModel;
use CodeIgniter\Entity;

/************************************************************
 *                                                           *
 *  .=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-.       *
 *   |                     ______                     |      *
 *   |                  .-"      "-.                  |      *
 *   |                 /            \                 |      *
 *   |     _          |              |          _     |      *
 *   |    ( \         |,  .-.  .-.  ,|         / )    |      *
 *   |     > "=._     | )(__/  \__)( |     _.=" <     |      *
 *   |    (_/"=._"=._ |/     /\     \| _.="_.="\_)    |      *
 *   |           "=._"(_     ^^     _)"_.="           |      *
 *   |               "=\__|IIIIII|__/="               |      *
 *   |              _.="| \IIIIII/ |"=._              |      *
 *   |    _     _.="_.="\          /"=._"=._     _    |      *
 *   |   ( \_.="_.="     `--------`     "=._"=._/ )   |      *
 *   |    > _.="                            "=._ <    |      *
 *   |   (_/                                    \_)   |      *
 *   |                                                |      *
 *   '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-='      *
 *                                                           *
 *     LEAVE EVERY HOPE OUT WHEN YOU COME IN ~ Bennito       *
 *************************************************************/

class User extends Entity
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAvatar() {
        if( isset($this->attributes['avatar']) && @$this->attributes['avatar'] != '') {
            return base_url('uploads/avatars/'.$this->attributes['avatar']);
        } else {
            return base_url('assets/img/default.jpg');
        }
        return null;
    }
    public function getPicture() {
        return $this->attributes['avatar'];
    }

    public function getAvatar2() {
        if( isset($this->attributes['avatar']) && @$this->attributes['avatar'] != '') {
            $path = 'uploads/avatars/'.$this->attributes['avatar'];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            return $base64;
        } else {
            $path = 'assets/img/default.jpg';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            return $base64;
        }
        return null;
    }

    public function getAvatar_file()
    {
        return 'uploads/avatars/'.$this->attributes['avatar'];
    }

    public function getName()
    {
        return $this->attributes['surname'].' '.$this->attributes['first_name'].' '.$this->attributes['last_name'];
    }

    public function getNameUser()
    {
        return $this->attributes['surname'].' '.$this->attributes['first_name'];
    }
    public function setPassword($password)
    {
        $ionAuth = new IonAuth();
        $this->attributes['password'] = $ionAuth->hashPassword($password);

        return $this;
    }

    /**
     * Users metadata
     */

    /****
     * @param mixed $meta_key Metadata Key
     * @param mixed $default The default value if metadata does not exist
     * @return bool|mixed
     */

    public function usermeta($meta_key, $default = false)
    {
        $val = \Config\Database::connect()->table('usersmeta')->getWhere(['userid' => $this->id, 'meta_key' => $meta_key])->getRow('meta_value');

        return $val ? $val : $default;
    }

    public function update_usermeta($meta_key, $meta_value)
    {
        if($this->usermeta_exists($meta_key)) {
                $result = \Config\Database::connect()->table('usersmeta')->where(['userid' => $this->id, 'meta_key' => $meta_key])->update(['meta_value' => $meta_value]);
            if($result) return true;

            return false;
        } else {
            return $this->add_usermeta($meta_key, $meta_value);
        }
    }

    public function add_usermeta($meta_key, $meta_value)
    {
        $result = \Config\Database::connect()->table('usersmeta')->insert(['userid' => $this->id, 'meta_key' => $meta_key, 'meta_value' => $meta_value]);
        if ($result) {
            return true;
        }

        return false;
    }

    public function usermeta_exists($meta_key)
    {
        $result = \Config\Database::connect()->table('usersmeta')->getWhere(['userid' => $this->id, 'meta_key' => $meta_key])->getRow();
        if ($result) {
            return true;
        }
        return false;
    }

    public function delete_usermeta($meta_key) {
        $result = \Config\Database::connect()->table('usersmeta')->where(['userid' => $this->id, 'meta_key' => $meta_key])->delete();
        if($result) return true;

        return false;
    }
    /**
     * PERMISSIONS
     */

    /**
     * Check user permission
     *
     * @param string $permission Permission to check
     * @return mixed|boolean True for allowed, false for denied
     */
    public function can($permission) {
        $ionAuth = new \App\Libraries\IonAuth();

        $capabilities = [];

        //Get group capabilities
        $groups = $ionAuth->getUsersGroups($this->id)->getResultObject();
        foreach ($groups as $group) {
            if($cap = json_decode($group->capabilities, true)) {
                $capabilities = array_merge($capabilities, $cap);
            }
        }
        //Get user specific capabilities
        $caps = $this->usermeta('_user_capabilities', json_encode([]));
        if($caps = json_decode($caps, true)) {
            $capabilities = array_merge($capabilities, $caps);
        }

        if(isset($capabilities[$permission]) && $capabilities[$permission] == 1) {
            $return = true;
        } else {
            $return = false;
        }
        //Give admin exclusive permissions
        if($ionAuth->isAdmin($this->id)) {
            $return = true;
        }

        return apply_filters('check_permission_'.$permission, $return);
        //return $return;
    }

    public function add_cap($permission, $allowed = 1) {
        $caps = $this->usermeta('_user_capabilities', json_encode([]));
        $caps = json_decode($caps, true);
        $caps[$permission] = $allowed;

        return $this->update_usermeta('_user_capabilities', json_encode($caps));
    }

    public function remove_cap($permission, $allowed = 1) {
        $caps = $this->usermeta('_user_capabilities', json_encode([]));
        $caps = json_decode($caps, true);
        unset($caps[$permission]);

        return $this->update_usermeta('_user_capabilities', json_encode($caps));
    }

    public function set_caps($capabilities = array()) {
        $caps = $this->usermeta('_user_capabilities', json_encode([]));
        $caps = json_decode($caps, true);
        $caps = array_merge($caps, $capabilities);

        return $this->update_usermeta('_user_capabilities', json_encode($caps));
    }
}