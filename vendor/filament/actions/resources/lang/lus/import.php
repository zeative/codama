<?php

return [

    'label' => ':Label import na',

    'modal' => [

        'heading' => ':Label import na',

        'form' => [

            'file' => [

                'label' => 'File',

                'placeholder' => 'CSV file upload rawh',

                'rules' => [
                    'duplicate_columns' => '{0} File hian column header awmlo pakhat aia tam anei theilo.|{1,*} File hian column header in ang a nei theilo: :columns.',
                ],

            ],

            'columns' => [
                'label' => 'Columns',
                'placeholder' => 'Select a column',
            ],

        ],

        'actions' => [

            'download_example' => [
                'label' => 'CSV example file download rawh',
            ],

            'import' => [
                'label' => 'Import',
            ],

        ],

    ],

    'notifications' => [

        'completed' => [

            'title' => 'Import completed',

            'actions' => [

                'download_failed_rows_csv' => [
                    'label' => 'A failed na chhan download rawh|A failed na chhan te download rawh',
                ],

            ],

        ],

        'max_rows' => [
            'title' => 'CSV file upload hi a lian lutuk',
            'body' => 'Vawikhatah row 1 ai a tam a import theiloh.|Vawikhatah rows :count ai a tam a import theiloh',
        ],

        'started' => [
            'title' => 'Import started',
            'body' => 'I import a intan a, row 1 background ah a insiam ang.|I export a intan a, rows :count background ah a insiam ang.',
        ],

    ],

    'example_csv' => [
        'file_name' => ':importer-example',
    ],

    'failure_csv' => [
        'file_name' => 'import-:import_id-:csv_name-failed-rows',
        'error_header' => 'error',
        'system_error' => 'System error, please contact support.',
        'column_mapping_required_for_new_record' => 'He :attribute column hi file a column nen a inmil lo, mahse records thar siamnan indah mil ngei a ngai.',
    ],

];
