<?php
/**
 * Created by PhpStorm.
 * User: TienLV
 * Date: 9/18/2017
 * Time: 4:01 PM
 *
 * Helpers of message

 */


class Message {

    //for user table
    const INSERT_USER_SUCCESS = 'The user has been successfully created';
    const UPDATE_USER_SUCCESS = 'The user has been successfully updated';
    const DELETE_USER_SUCCESS = 'The user has been successfully deleted';
    const RESET_PASSWORD_SUCCESS = 'The password has been reset';

    //for department table
    const INSERT_DEPARTMENT_SUCCESS = 'The department has been successfully created';
    const UPDATE_DEPARTMENT_SUCCESS = 'The department has been successfully updated';
    const DELETE_DEPARTMENT_SUCCESS = 'The department has been successfully deleted';
    const DELETE_DEPARTMENT_WARNING = 'Failed. Existing personnel in the department ';
}
