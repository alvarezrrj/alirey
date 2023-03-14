<?php

use Illuminate\Support\Facades\App;

return [
   'mp_token' => env('MP_TOKEN'),
   'mp_pub_key' => env('MP_PUB_KEY'),
   'local_notification_url' => 'https://aa44914e-dbc5-46d5-acf8-174fb163d2df.mock.pstmn.io?source_news=webhooks',
   'dist_notification_url_name' => 'webhooks.mp',
];
