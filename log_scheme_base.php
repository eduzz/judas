<?php

$array = [
    "*agent": ["procedure", "system", "user", "support"],
    "*event": [
        "*app": ["checkoutsun", "myeduzz", "checkoutleg", "myeduzzleg", "nutror", "next"],
        "*module": "string",
        "*action": "string",
        "*data": [
            "id": "int"
        ]
    ],
    "user": [
        "*id": "int",
        "*username": "string",
        "*ip": "^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$"
    ],
    "additional_data": [
        // Any other data goes here
    ]

];
