<?php

return [
    'models' => array(
        'userchecklist' => [
            'table' => 'userchecklist'            
        ],
        
        'checklist' => [
            'table' => 'checklist',
            'id' => 'id'
        ]
    ),
    
    'relationships' => [

        // Each user may have multiple messages
        [
            'type'  => 'oneToMany',
            'owner' => 'user',
            'items' => 'message'
        ],
        
        [
            'type'  => 'manyToMany',
            'left' => 'checklist',
            'right' => 'user',
            'pivot' => 'userchecklist',
            'pivotOptions' => array(
                'leftKey'  => 'checkid',
                'rightKey' => 'userid',
            )
        ]        
    ]
];