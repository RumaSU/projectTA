<?php

return [
    'steps' => [
        'basic_info' => [
            [
                'name' => 'fullname',
                'icon' => 'fas fa-user',
                'label' => [
                    'l_ariaLabelledBy' => 'fullnameForm',
                    'l_text' => 'Fullname',
                ],
                'type_input' => 'default',
                'input' => [
                    'i_type' => 'text',
                    'i_type_as' => 'text',
                    'i_id' => 'fullnameFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Fullname',
                    'i_autocomplete' => 'new-password',
                    'i_aria_autocomplete' => 'none',
                ],
                'wire_model' => [
                    'var_model' => 'inp_fullname',
                ],
                'error' => [
                    'key' => 'inp_fullname',
                ],
            ],
            [
                'name' => 'birthday',
                'icon' => 'fas fa-calendar-days',
                'label' => [
                    'l_ariaLabelledBy' => 'birthdayForm',
                    'l_text' => 'Birthday',
                ],
                'type_input' => 'default',
                'input' => [
                    'i_type' => 'text',
                    'i_type_as' => 'date',
                    'i_id' => 'birthdayFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Birthday',
                    'i_autocomplete' => 'new-password',
                    'i_aria_autocomplete' => 'none',
                ],
                'wire_model' => [
                    'var_model' => 'inp_birthday',
                ],
                'error' => [
                    'key' => 'inp_birthday',
                ],
            ],
            [
                'name' => 'gender',
                'type' => 'single',
                // 'icon' => 'fas fa-venus-mars',
                'label' => [
                    'l_ariaLabelledBy' => 'genderForm',
                    'l_text' => 'Gender',
                ],
                'type_input' => 'select',
                'input' => [
                    'i_id' => 'genderFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Gender',
                    'i_selectType' => 'default', // custom or default
                    'i_autocomplete' => 'off',
                    'i_aria_autocomplete' => 'none',
                    'i_select_registered' => [
                        // ['id' => 'genderFormValTitle', 'text' => 'Gender', 'value' => null, 'selected' => true, 'disabled' => true], 
                        ['id' => 'genderFormValMale', 'text' => 'Male', 'value' => 'male', 'selected' => false, 'disabled' => false], 
                        ['id' => 'genderFormValFemale', 'text' => 'Female', 'value' => 'female', 'selected' => false, 'disabled' => false], 
                        ['id' => 'genderFormValNotSay', 'text' => 'Prefer not to say', 'value' => 'not_say', 'selected' => false, 'disabled' => false], 
                    ],
                ],
                'wire_model' => [
                    'var_model' => 'inp_gender',
                ],
                'error' => [
                    'key' => 'inp_gender',
                ],
            ],
        ],
        
        'credentials' => [
            [
                'name' => 'email',
                'icon' => 'fas fa-user',
                'label' => [
                    'l_ariaLabelledBy' => 'emailForm',
                    'l_text' => 'Email',
                ],
                'type_input' => 'default',
                'input' => [
                    'i_type' => 'email',
                    'i_type_as' => 'email',
                    'i_id' => 'emailFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Email',
                    'i_autocomplete' => 'new-password',
                    'i_aria_autocomplete' => 'none',
                ],
                'wire_model' => [
                    'var_model' => 'inp_email',
                ],
                'error' => [
                    'key' => 'inp_email',
                ],
            ],
            [
                'name' => 'password',
                'icon' => 'fas fa-lock',
                'label' => [
                    'l_ariaLabelledBy' => 'passwordForm',
                    'l_text' => 'Password',
                ],
                'type_input' => 'default',
                'input' => [
                    'i_type' => 'password',
                    'i_type_as' => 'password',
                    'i_id' => 'passwordFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Password',
                    'i_autocomplete' => 'new-password',
                    'i_aria_autocomplete' => 'none',
                ],
                'wire_model' => [
                    'var_model' => 'inp_password',
                ],
                'error' => [
                    'key' => 'inp_password',
                ],
            ],
            [
                'name' => 'password_confirmation',
                'icon' => 'fas fa-lock',
                'label' => [
                    'l_ariaLabelledBy' => 'password_confirmationForm',
                    'l_text' => 'Confirm Password',
                ],
                'type_input' => 'default',
                'input' => [
                    'i_type' => 'password',
                    'i_type_as' => 'password',
                    'i_id' => 'password_confirmationFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Confirm Password',
                    'i_autocomplete' => 'new-password',
                    'i_aria_autocomplete' => 'none',
                ],
                'wire_model' => [
                    'var_model' => 'inp_password_confirmation',
                ],
                'error' => [
                    'key' => 'inp_password_confirmation',
                ],
            ],
        ],
        
        
        
        
        'fullname' => [
            [
                'name' => 'fullname',
                'icon' => 'fas fa-user',
                'label' => [
                    'l_ariaLabelledBy' => 'fullnameForm',
                    'l_text' => 'Fullname',
                ],
                'type_input' => 'default',
                'input' => [
                    'i_type' => 'text',
                    'i_type_as' => 'text',
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
        
        'birth_gender' => [
            [
                'name' => 'birthday',
                'icon' => 'fas fa-calendar-days',
                'label' => [
                    'l_ariaLabelledBy' => 'birthdayForm',
                    'l_text' => 'Birthday',
                ],
                'type_input' => 'default',
                'input' => [
                    'i_type' => 'text',
                    'i_type_as' => 'date',
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
                'name' => 'gender',
                'type' => 'single',
                // 'icon' => 'fas fa-venus-mars',
                'label' => [
                    'l_ariaLabelledBy' => 'genderForm',
                    'l_text' => 'Gender',
                ],
                'type_input' => 'select',
                'input' => [
                    'i_id' => 'genderFormRegister',
                    'i_required' => false,
                    'i_placeholder' => 'Gender',
                    'i_selectType' => 'default', // custom or default
                    'i_select_registered' => [
                        // ['id' => 'genderFormValTitle', 'text' => 'Gender', 'value' => null, 'selected' => true, 'disabled' => true], 
                        ['id' => 'genderFormValMale', 'text' => 'Male', 'value' => 'male', 'selected' => false, 'disabled' => false], 
                        ['id' => 'genderFormValFemale', 'text' => 'Female', 'value' => 'female', 'selected' => false, 'disabled' => false], 
                        ['id' => 'genderFormValNotSay', 'text' => 'Prefer not to say', 'value' => 'not_say', 'selected' => false, 'disabled' => false], 
                    ],
                ],
                'wire_model' => [
                    'var_model' => 'inp_gender',
                ],
                'error' => [
                    'key' => 'inp_gender',
                ],
            ],
        ],
    ],
    
    
];