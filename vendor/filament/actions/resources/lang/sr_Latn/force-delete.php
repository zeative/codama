<?php

return [

    'single' => [

        'label' => 'Prisilno izbriši',

        'modal' => [

            'heading' => 'Prisilno izbriši :label',

            'actions' => [

                'delete' => [
                    'label' => 'Izbriši',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => 'Izbrisano',
            ],

        ],

    ],

    'multiple' => [

        'label' => 'Prisilno izbriši izabrano',

        'modal' => [

            'heading' => 'Prisilno izbriši izabrano',

            'actions' => [

                'delete' => [
                    'label' => 'Izbriši',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => 'Izbrisano',
            ],

            'deleted_partial' => [
                'title' => 'Izbrisano :count od :total',
                'missing_authorization_failure_message' => 'Nemate dozvolu da izbrišite :count.',
                'missing_processing_failure_message' => ':count ne može da se izbriše.',
            ],

            'deleted_none' => [
                'title' => 'Brisanje nije uspelo',
                'missing_authorization_failure_message' => 'Nemate dozvolu da izbrišite :count.',
                'missing_processing_failure_message' => ':count ne može da se izbriše.',
            ],
        ],

    ],

];
