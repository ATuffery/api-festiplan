<?php
http_response_code(isset($http_code) ? $http_code : 500);
echo json_encode(isset($json) ? $json : "", JSON_UNESCAPED_UNICODE);