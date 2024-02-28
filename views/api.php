<?php
http_response_code($http_code);
echo json_encode($json, JSON_UNESCAPED_UNICODE);