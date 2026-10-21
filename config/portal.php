<?php

return [
    // Flip these in .env (or wire to a database settings table later for an in-app admin toggle)
    'theory_open' => env('PORTAL_THEORY_OPEN', true),
    'mcq_open'    => env('PORTAL_MCQ_OPEN', true),
];
