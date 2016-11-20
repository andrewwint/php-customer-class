<?php
namespace Core\User;
use Core\User\Address as Address;
use Core\User\Phone as Phone;
use Core\User\Email as Email;

$before = microtime(true);

class Address
{
    public $id;

    public $label;

    public $type;

    public $address1;

    public $address2;

    public $state;

    public $zip;

    public $default;

    public function setAddress($data) 
    {
        $this->id       = $data->id;
        $this->label    = $data->label;
        $this->type     = $data->type;
        $this->address1 = $data->street[0];
        $this->address2 = $data->street[1];
        $this->state    = $data->state;
        $this->zip      = $data->zip;
        $this->default  = $data->default;

        return $this;
    }
}

class Phone
{
    public $id;

    public $type;

    public $number;

    public $extention;

    public $default;

    public function setPhone($data) 
    {
        $this->id        = $data->id;
        $this->type      = $data->type;
        $this->number    = $data->number;
        $this->extention = $data->extention;
        $this->default   = $data->default;
        return $this;
    }

}


class Email
{
    public $id;

    public $type;

    public $email;

    public $default;

    public function setEmail($data) 
    {
        $this->id       = $data->id;
        $this->type     = $data->type;
        $this->email    = ($data->address) ?: $data->email;
        $this->default  = $data->default;

       return $this;
    }
}


class Customer
{
    public $firstName;

    public $lastName;

    public $email   = array();

    public $phone   = array();

    public $address = array();

    public function setUserByJSON($json)
    {
        $data = json_decode($json);
        
        $this->firstName = $data->firstName;
        $this->lastName  = $data->lastName;
        $this->setUserEmailByJSON($data->email);
        $this->setUserPhoneByJSON($data->phone);
        $this->setUserAddressByJSON($data->address);
    }

    protected function setUserEmailByJSON($data)
    {
        foreach ($data as $value) {
            $_newemail = new Email();
            if(array_key_exists('default', $value)){
                $this->email['default'] = $_newemail->setEmail($value);
            }else{
                $this->email[] = $_newemail->setEmail($value);
            }
            unset($_newemail);
        }
    }

    protected function setUserPhoneByJSON($data)
    {
        foreach ($data as $value) {
            $_newphone = new Phone();
            if(array_key_exists('default', $value)){
                $this->phone['default'] = $_newphone->setPhone($value);
            }else{
                $this->phone[] = $_newphone->setPhone($value);
            }
            unset($_newphone);
        }
    }

    protected function setUserAddressByJSON($data)
    {
        foreach ($data as $value) {
            $_newaddress = new Address();
            if(array_key_exists("default", $value)){
                $this->address['default'] = $_newaddress->setAddress($value);
            }else{
                $this->address[] = $_newaddress->setAddress($value);
            }
            unset($_newaddress);
        }
    }
}

$customer = <<<JSON
{
    "firstName": "Jonathon",
    "lastName": "Doe",
    "email": [
        {
            "id": "2311",
            "type": "work",
            "address": "jdoe@somewhere.org",
            "default": true
        },
        {
            "id": "7775",
            "type": "personal",
            "email": "johnathon.doe@gmail.com"
        }
    ],
    "phone": [
        {
            "id": "7673",
            "type": "work",
            "number": "2124583322",
            "extention": "223",
            "default": true
        },
        {
            "id": "24332",
            "type": "cell",
            "number": "9173348484"
        }
    ],
    "address": [
        {
            "id": "232",
            "label": "Home",
            "type": "shipping",
            "street": [
                "4854 Embassy Drive",
                "Building 8"
            ],
            "city": "Arlington",
            "state": "VA",
            "zip": "20184"
        },
        {
            "id": "233",
            "label": "Work",
            "type": "shipping",
            "street": [
                "4854 South 4th Street",
                "Suite 705"
            ],
            "city": "Newark",
            "state": "NJ",
            "zip": "10475-4392",
            "default": true
        },
        {
            "id": "252",
            "label": "Parents",
            "type": "billing",
            "street": [
                "4854 South 4th Street",
                "Suite 705"
            ],
            "city": "Newark",
            "state": "NJ",
            "zip": "10475-4392"
        }
    ]
}
JSON;

$newcustomer = new \Core\User\Customer();
$newcustomer->setUserByJSON($customer);

print_r($newcustomer);

echo "Default Phone number " . $newcustomer->phone['default']->number . "\r\n";

print_r($newcustomer->email['default']);

$after = microtime(true);
echo ($after-$before) . " sec/serialize\n";

?>