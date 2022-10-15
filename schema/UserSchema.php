<?php

namespace Schema;

class UserSchema
{
    /**
	 * Create login schema.
	 */
    public function login()
    {
        $schema = array(
            'username' => array(
                'required' => true,
                'type' => 'string',
                'validate_callback' => function ($value, $request, $key) {
                    return true;
                },
            ),
            'password' => array(
                'required' => true,
                'type' => 'string',
            ),
        );
        return $schema;
    }

}
