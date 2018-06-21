<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

return [
    'user.first_name.not_blank'   => 'First name is required',
    'user.first_name.min_length'  => 'First name must be at least {{ limit }} characters long',
    'user.first_name.max_length'  => 'First name cannot be longer than {{ limit }} characters',
    'user.last_name.not_blank'    => 'Last name is required',
    'user.last_name.min_length'   => 'Last name must be at least {{ limit }} characters long',
    'user.last_name.max_length'   => 'Last name cannot be longer than {{ limit }} characters',
    'user.email.not_blank'        => 'Email is required',
    'user.email.invalid_format'   => 'Invalid email address',
    'user.email.min_length'       => 'Email must be at least {{ limit }} characters long',
    'user.email.max_length'       => 'Email cannot be longer than {{ limit }} characters',
    'user.email.not_unique'       => 'Email already exists',
    'user.password.not_blank'     => 'Password is required',
    'user.password.min_length'    => 'Password must be at least {{ limit }} characters long',
    'user.password.max_length'    => 'Password cannot be longer than {{ limit }} characters',
    'user.password.not_confirmed' => 'Password is not confirmed'
];