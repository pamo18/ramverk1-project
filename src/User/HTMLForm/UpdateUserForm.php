<?php

namespace Pamo\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Pamo\User\User;

/**
 * Example of FormModel implementation.
 */
class UpdateUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $username)
    {
        parent::__construct($di);
        $user = $this->getItemDetails($username);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Create user",
            ],
            [
                "id" => [
                    "type"        => "hidden",
                    "value"       => $user->id,
                ],

                "username" => [
                    "type"        => "text",
                    "value"       => $user->username,
                ],

                "email" => [
                    "type"        => "email",
                    "value"       => $user->email
                ],

                "old-password" => [
                    "type"        => "password",
                    "placeholder" => "To update password",
                ],

                "new-password" => [
                    "type"        => "password",
                    "placeholder" => "To update password",
                ],

                "repeat-new-password" => [
                    "type"        => "password",
                    "placeholder" => "To update password",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return User
     */
    public function getItemDetails($username) : object
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("username", $username);
        return $user;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $username      = $this->form->value("username");
        $email         = $this->form->value("email");
        $oldPassword      = $this->form->value("old-password");
        $newPassword      = $this->form->value("new-password");
        $newPasswordAgain = $this->form->value("repeat-new-password");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("id", $this->form->value("id"));

        if ($newPassword) {
            $res = $user->verifyPassword($user->username, $oldPassword);

            if (!$res) {
                $this->form->rememberValues();
                $this->form->addOutput("Old password did not match.");
                return false;
            }
            // Check password matches
            if ($newPassword !== $newPasswordAgain) {
                $this->form->rememberValues();
                $this->form->addOutput("New password did not match.");
                return false;
            }

            $user->setPassword($newPassword);
        }

        $user->username = $username;
        $user->email = $email;
        $user->save();

        $this->di->session->set("user", [
            "id" => $user->id,
            "username" => $user->username,
            "email" => $user->email
        ]);

        $this->username = $user->username;

        $this->form->addOutput("Profile updated!");
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("user/update/$this->username")->send();
    }
}
