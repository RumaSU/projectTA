<?php

return [
    'steps' => [
        'fullname' => [
            [
                'name' => 'fullname',
                'icon' => 'fas fa-user',
                'label' => [
                    'l_ariaLabelledBy' => 'fullnameForm',
                    'l_text' => 'Fullname',
                ],
                'input' => [
                    'i_type' => 'text',
                    'i_id' => 'fullnameFormRegister',
                    'i_required' => true,
                    'i_placeholder' => 'Fullname',
                ],
                'wire_model' => [
                    'var_model' => 'inp_fullname',
                ],
                'error' => [
                    'key' => 'inp_fullname',
                ],
            ],
        ],
        
        'birth_gender' => [
            [
                'name' => 'birthday',
                'icon' => 'fas fa-user',
                'label' => [
                    'l_ariaLabelledBy' => 'birthdayForm',
                    'l_text' => 'Birthday',
                ],
                'input' => [
                    'i_type' => 'date',
                    'i_id' => 'birthdayFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Birthday',
                ],
                'wire_model' => [
                    'var_model' => 'inp_birthday',
                ],
                'error' => [
                    'key' => 'inp_birthday',
                ],
            ],
            [
                'name' => 'fullname',
                'icon' => 'fas fa-user',
                'label' => [
                    'l_ariaLabelledBy' => 'fullnameForm',
                    'l_text' => 'Fullname',
                ],
                'input' => [
                    'i_type' => 'text',
                    'i_id' => 'fullnameFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Fullname',
                ],
                'wire_model' => [
                    'var_model' => 'inp_fullname',
                ],
                'error' => [
                    'key' => 'inp_fullname',
                ],
            ],
        ],
    ],
];