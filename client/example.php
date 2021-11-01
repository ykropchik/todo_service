#!/usr/bin/env
<?php

use Client\TodoService;

require_once dirname(__DIR__).'/client/vendor/autoload.php';

$todoService = new TodoService("http://138.197.185.17", "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1VTRVIiLCJST0xFX0FETUlOIl0sInVzZXJuYW1lIjoieWtyb3BjaGlrIn0.YyHP88IInsa5bsbkX6Tmu3k7I7jtONwp-YHBcStU7bc");
$result = $todoService->getTodoList();
echo $result->getData();