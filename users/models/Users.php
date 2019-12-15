<?php

use Phalcon\Security;
use Phalcon\Validation;

class Users extends \Phalcon\Mvc\Model
{

    /**
     * @var Validation
     */
    private $validator;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $login;

    /**
     *
     * @var string
     */
    public $password;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("main");
        $this->setSource("users");

        $this->validator = new Validation();
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function validation()
    {
        $this->validator->add(['login'], new Validation\Validator\PresenceOf(['message' => 'Login is required']));
        $this->validator->add(['password'], new Validation\Validator\PresenceOf(['message' => 'Password is required']));

        $this->validate($this->validator);

        return !$this->validationHasFailed();
    }

    public function auth(Security $security)
    {

    }
}
