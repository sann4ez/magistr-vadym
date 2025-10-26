<?php

return [

    /*
     * This model will be used to Term.
     */
    'term_model' => \Fomvasss\SimpleTaxonomy\Models\Term::class,

    /*
     * Apply global scopes for model terms.
     */
    'term_prepare_scopes' => [
        \Fomvasss\SimpleTaxonomy\Models\Scopes\WeightScope::class,
    ],
];
