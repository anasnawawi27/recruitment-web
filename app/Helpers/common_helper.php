<?php

if (!function_exists('alert_message')) {

    function alert_message($type, $message)
    {
        $message = '<div class="alert alert-' . $type . ' alert-dismissible fade show mt-3 text-left" role="alert">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';
        return $message;
    }
}

if (!function_exists('number')) {

    function number($val, $currDecimalDigit = 2, $currSymbol = '')
    {
        return $currSymbol . number_format($val, $currDecimalDigit, settings('number_decimal_separator'), settings('number_thousand_separator'));
    }
}

if (!function_exists('rupiah')) {

    function rupiah($val)
    {
        $value = number($val);
        return 'Rp ' . $value;
    }
}

if (!function_exists('ordinal_number')) {
    function ordinal_number($number)
    {
        $ends = array(
            'th',
            'st',
            'nd',
            'rd',
            'th',
            'th',
            'th',
            'th',
            'th',
            'th'
        );
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
    }
}

if (!function_exists('settings')) {

    function settings($key)
    {
        if ($key) {
            $session = service('session');
            if ($session->has('settings')) {
                return $session->get('settings')[$key];
            } else {
                $db = \Config\Database::connect();
                $settings = $db->table('settings')->get()->getResult();
                foreach ($settings as $setting) {
                    $data[$setting->key] = $setting->value;
                }
                $session->set('settings', $data);
                return $data[$key];
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('set_password')) {

    function set_password(string $password)
    {
        $config = config('Auth');
        $hashOptions = [
            'cost' => $config->hashCost
        ];
        $setPasswordUser = password_hash(base64_encode(hash('sha384', $password, true)), $config->hashAlgorithm, $hashOptions);
        return $setPasswordUser;
    }
}

if (!function_exists('input_filter')) {

    function input_filter($arrayData)
    {
        foreach ($arrayData as $key => $data) {
            if ($data == '') {
                $arrayData[$key] = NULL;
            }
        }
        return $arrayData;
    }
}

if (!function_exists('encode')) {

    function encode($string)
    {
        $encrypter = service('encrypter');
        return bin2hex($encrypter->encrypt($string));
    }
}

if (!function_exists('decode')) {

    function decode($string)
    {
        $encrypter = service('encrypter');
        return $encrypter->decrypt(hex2bin($string));
    }
}

if (!function_exists('invoice_code')) {
    function invoice_code()
    {
        $db = db_connect();
        $last_code = $db->query("SELECT IFNULL(MAX(code),0) last_code FROM invoices")->getRow()->last_code;

        $last_code++;
        if ($last_code < 10) {
            $last_code = '000' . $last_code;
        } elseif ($last_code < 100) {
            $last_code = '00' . $last_code;
        } elseif ($last_code < 1000) {
            $last_code = '0' . $last_code;
        }
        return $last_code;
    }
}

if (!function_exists('in_groups')) {
    function in_groups($group)
    {
        $db = db_connect();
        $builder = $db->table('auth_groups_users a');
        $builder->join('auth_groups b', 'b.id = a.group_id', 'left');
        $builder->where(['a.user_id' => user_id(), 'b.name' => $group]);
        $data = $builder->find();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }
}

