<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Ho_Chi_Minh');

define('BACKEND_DIRECTORY', 'admin');

define('AUTH', 'HTVIETNAM_');
define('ASSET_BACKEND', 'public/backend/');

define('BASE_URL', 'https://midtownpmh.com/');
define('_BASE_URL', 'https://midtownpmh.com/');
define('HTSUFFIX', '.html');

define('TONGQUAN', 'tong-quan.html');
define('AUTH_CURRENCY', 'HTVIETNAM_currency');

define('SEARCH', 'tim-kiem.html');
define('SEARCH_PAGE', 'tim-kiem/trang-([0-9]+).html');

define('DEBUG', 0);
define('COMPRESS', 0);
define('CMS_NAME', 'HT VIETNAM CMS 3.0');
define('API_WIDGET', 'http://widget.htweb.vn');

// File upload configuration
define('TARGET_DIR', 'upload_frontend/');
// define('ALLOW_TYPES', 'doc|docx|pdf|xls|xlsx|ppt|pptx');
define('ALLOW_TYPES', 'pdf|jpg');
define('MAX_SIZE', '5120'); // 20MB : 1024 KB = 1 MB

define('HTDBHOST', 'DIEN_VAO_DAY');
define('HTDBUSER', 'DIEN_VAO_DAY');
define('HTDBPASS', 'DIEN_VAO_DAY');
define('HTDBNAME', 'DIEN_VAO_DAY');
