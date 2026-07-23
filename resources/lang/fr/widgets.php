<?php

declare(strict_types=1);

return [

    'actions' => [
        'view_all' => 'Voir tout',
        'view_more' => 'Voir plus',
    ],

    'empty_state' => [
        'heading' => 'Aucune donnée',
        'description' => 'Il n\'y a rien à afficher pour le moment.',
    ],

    'metric' => [
        'trend' => [
            'up' => 'Tendance à la hausse',
            'down' => 'Tendance à la baisse',
            'neutral' => 'Pas de changement notable',
        ],
        'comparison' => 'Par rapport à la période précédente',
    ],

    'goal' => [
        'remaining' => 'Restant',
        'reached' => 'Objectif atteint',
        'exceeded' => 'Dépassé de :value',
        'of_target' => ':current sur :target',
        'deadline' => 'Échéance : :date',
        'overdue' => 'En retard',
        'progress' => 'Progression de :label : :percentage',
    ],

    'breakdown' => [
        'total' => 'Total',
        'share' => ':label : :value (:percentage du total)',
    ],

    'detail' => [
        'placeholder' => '-',
    ],

    'bullet' => [
        'target' => 'Objectif : :value',
        'benchmark' => 'Référence : :value',
        'status' => [
            'below' => 'En dessous de l\'objectif',
            'met' => 'Objectif atteint',
            'above' => 'Au-dessus de l\'objectif',
        ],
    ],

    'trend' => [
        'comparison' => 'Par rapport à la période précédente',
        'summary' => 'Graphique de :label. Valeur actuelle : :value.',
    ],

    'composition' => [
        'total' => 'Total : :value',
    ],

    'funnel' => [
        'overall' => 'Conversion globale : :percentage',
    ],

    'timeline' => [
        'today' => 'Aujourd\'hui',
        'yesterday' => 'Hier',
    ],

];
