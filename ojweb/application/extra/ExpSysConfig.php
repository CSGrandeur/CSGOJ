<?php

return [
    'SCHOOL_RANK_TEAMNUM' => 4, //计算学校排名的队伍个数
    'PRINT_STATUS'    =>[
        0    =>    'Waiting',
        1    =>    'Printed',
        2    =>    'Denied',
    ],
    'PRINT_STATUS_HTML'    =>[
        0    =>    ['info', 'Waiting'],
        1    =>    ['success', 'Printed'],
        2    =>    ['danger', 'Denied'],
    ],
    // userinfo_rule、userinfo_msg用于验证用户注册信息的预定义规则
    'userinfo_rule'    => [
        'user_id'    => ['require', 'min:5', 'max:30', '/^[a-zA-Z0-9_]+$/'],
        'nick'         => 'max:32',
        'email'     => 'max:100',
        'school'     => 'max:64',
        'password'     => 'min:6|max:64'
    ],
    'userinfo_msg' => [
        'user_id.require'             => 'User ID needed.',
        'user_id.min'                 => 'User ID should have more than 5 characters.',
        'user_id.max'                 => 'User ID should not exceed 30 characters.',
        'user_id./^[a-zA-Z0-9_]+$/' => 'Only number, letters and underlines are allowed for User ID.',
        'nick.max'                    => 'Team name should not exceed 30 characters.',
        'email.max'                    => 'Members should not exceed 100 characters.',
        'school.max'                => 'School name should not exceed 64 characters.',
        'password.min'                => 'Password should have more than 6 characters.',
        'password.max'                => 'Password should have less than 64 characters.'
    ],
];
