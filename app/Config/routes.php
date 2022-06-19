<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
Router::parseExtensions('rss', 'json', 'xml', 'csv');

Router::mapResources(array('api', 'pages'));

Router::connect('/', array('plugin' => null, 'controller' => 'layout', 'action' => 'index'));
//Router::connect('/admin', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'login'));
//Router::connect('/affiliate', array('prefix' => 'affiliate', 'affiliate' => true, 'controller' => 'users', 'action' => 'login'));
//Router::connect('/admin/acl', array('prefix' => 'admin', 'admin' => true, 'plugin' => 'Acl', 'controller' => 'Acl', 'action' => 'index'));

// Luxon
Router::connect('/payments/LuxonPay/callback', array('plugin' => 'Payments', 'controller' => 'LuxonPay', 'action' => 'callback'));
Router::connect('/payments/LuxonPay/success', array('plugin' => 'Payments', 'controller' => 'LuxonPay', 'action' => 'success'));

//BlueOcean
Router::connect('/games/BlueOceanWallet/callback', array('plugin' => 'Games', 'controller' => 'BlueOceanWallet', 'action' => 'callback'));
Router::connect('/games/blueocean/game/*', array('plugin' => 'Games', 'controller' => 'BlueOcean', 'action' => 'game'));
Router::connect('/games/blueocean/getDailyReport/*', array('plugin' => 'Games', 'controller' => 'BlueOcean', 'action' => 'getDailyReport'));
Router::connect('/games/blueocean/getDailyReportMulti/*', array('plugin' => 'Games', 'controller' => 'BlueOcean', 'action' => 'getDailyReportMulti'));

//WNetGame
Router::connect('/WNetGame/WNetGameAPI', array('plugin' => 'Games', 'controller' => 'WNetGameAPIs', 'action' => 'index'));
Router::connect('/games/wnetgames/game/*', array('plugin' => 'Games', 'controller' => 'WNetGame', 'action' => 'game'));

//ForumPay
Router::connect('/payments/ForumPay/deposit', array('plugin' => 'Payments', 'controller' => 'ForumPay', 'action' => 'deposit'));
Router::connect('/payments/ForumPay/callback', array('plugin' => 'Payments', 'controller' => 'ForumPay', 'action' => 'callback'));
Router::connect('/payments/ForumPay/success', array('plugin' => 'Payments', 'controller' => 'ForumPay', 'action' => 'success'));
Router::connect('/payments/ForumPay/failed', array('plugin' => 'Payments', 'controller' => 'ForumPay', 'action' => 'failed'));


//BridgerPay
Router::connect('/payments/BridgerPay/deposit', array('plugin' => 'Payments', 'controller' => 'BridgerPay', 'action' => 'deposit'));
Router::connect('/payments/BridgerPay/callback', array('plugin' => 'Payments', 'controller' => 'BridgerPay', 'action' => 'callback'));
Router::connect('/payments/BridgerPay/success', array('plugin' => 'Payments', 'controller' => 'BridgerPay', 'action' => 'success'));
Router::connect('/payments/BridgerPay/failed', array('plugin' => 'Payments', 'controller' => 'BridgerPay', 'action' => 'failed'));

//Aninda
Router::connect('/payments/Aninda/deposit', array('plugin' => 'Payments', 'controller' => 'Aninda', 'action' => 'deposit'));
Router::connect('/payments/Aninda/withdraw', array('plugin' => 'Payments', 'controller' => 'Aninda', 'action' => 'withdraw'));
Router::connect('/payments/Aninda/callback', array('plugin' => 'Payments', 'controller' => 'Aninda', 'action' => 'callback'));
Router::connect('/payments/Aninda/success', array('plugin' => 'Payments', 'controller' => 'Aninda', 'action' => 'success'));
Router::connect('/payments/Aninda/failed', array('plugin' => 'Payments', 'controller' => 'Aninda', 'action' => 'failed'));


//VIPPASS
Router::connect('/payments/vippass/deposit', array('plugin' => 'Payments', 'controller' => 'Vippass', 'action' => 'deposit'));
Router::connect('/payments/vippass/callback', array('plugin' => 'Payments', 'controller' => 'Vippass', 'action' => 'callback'));
Router::connect('/payments/vippass/redirect/:id', array('plugin' => 'Payments', 'controller' => 'Vippass', 'action' => 'check_and_redirect'), array('pass' => array('id'), 'id'=>'[0-9]+'));
Router::connect('/payments/vippass/success', array('plugin' => 'Payments', 'controller' => 'Vippass', 'action' => 'success'));
Router::connect('/payments/vippass/failed', array('plugin' => 'Payments', 'controller' => 'Vippass', 'action' => 'failed'));

//AstroPay
Router::connect('/payments/astropay/deposit', array('plugin' => 'Payments', 'controller' => 'Astropay', 'action' => 'deposit'));
Router::connect('/payments/astropay/callback', array('plugin' => 'Payments', 'controller' => 'Astropay', 'action' => 'callback'));
Router::connect('/payments/astropay/redirect', array('plugin' => 'Payments', 'controller' => 'Astropay', 'action' => 'redirect_to_success'));
Router::connect('/payments/astropay/success', array('plugin' => 'Payments', 'controller' => 'Astropay', 'action' => 'success'));

//WonderlandPay
Router::connect('/payments/wonderlandpay/deposit', array('plugin' => 'Payments', 'controller' => 'WonderlandPay', 'action' => 'deposit'));

//uQualify
Router::connect('/payments/uqualify/deposit', array('plugin' => 'Payments', 'controller' => 'UQualify', 'action' => 'deposit'));
Router::connect('/payments/astropay/callback', array('plugin' => 'Payments', 'controller' => 'UQualify', 'action' => 'callback'));
Router::connect('/payments/uqualify/success', array('plugin' => 'Payments', 'controller' => 'UQualify', 'action' => 'success'), array('pass' => array('id')));
Router::connect('/payments/uqualify/cancel', array('plugin' => 'Payments', 'controller' => 'UQualify', 'action' => 'cancel'), array('pass' => array('id')));

//PAYMENTS
Router::connect('/payments/deposits/index', array('plugin' => 'Payments', 'controller' => 'Deposits', 'action' => 'index'));
Router::connect('/payments/withdraws/index', array('plugin' => 'Payments', 'controller' => 'Withdraws', 'action' => 'index'));

// CUSTOMER IO PlUGIN ROUTES

Router::connect('/CustomerIO/Customers/updateCustomerIOAttributes', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'updateCustomerIOAttributes'));


//Customers
Router::connect('/CustomerIO/Customers/addUpdateCustomer', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'addUpdateCustomer'));
Router::connect('/CustomerIO/Customers/deleteCustomer', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'deleteCustomer'));
Router::connect('/CustomerIO/Customers/addUpdateCustomerDevice', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'addUpdateCustomerDevice'));
Router::connect('/CustomerIO/Customers/deleteCustomerDevice', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'deleteCustomerDevice'));
Router::connect('/CustomerIO/Customers/suppressCustomerProfile', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'suppressCustomerProfile'));
Router::connect('/CustomerIO/Customers/unsuppressCustomerProfile', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'unsuppressCustomerProfile'));
Router::connect('/CustomerIO/Customers/customUnsubscribeHandling', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'customUnsubscribeHandling'));
Router::connect('/CustomerIO/Customers/getCustomersByEmail', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'getCustomersByEmail'));
Router::connect('/CustomerIO/Customers/searchForCustomers', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'searchForCustomers'));
Router::connect('/CustomerIO/Customers/lookupCustomerAttributes', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'lookupCustomerAttributes'));
Router::connect('/CustomerIO/Customers/listCustomersAndAttributes', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'listCustomersAndAttributes'));
Router::connect('/CustomerIO/Customers/lookupMessagesSentToCustomer', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'lookupMessagesSentToCustomer'));
Router::connect('/CustomerIO/Customers/lookupCustomerSegments', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'lookupCustomerSegments'));
Router::connect('/CustomerIO/Customers/lookupMessagesSentToCustomer', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'lookupMessagesSentToCustomer'));
Router::connect('/CustomerIO/Customers/lookupCustomerActivities', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Customers', 'action' => 'lookupCustomerActivities'));
//
//Activities
Router::connect('/CustomerIO/Activities/listActivities', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Activities', 'action' => 'listActivities'));
//Broacasts
Router::connect('/CustomerIO/Broadcasts/triggerBroadcast', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'triggerBroadcast'));
Router::connect('/CustomerIO/Broadcasts/getStatusBroadcast', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getStatusBroadcast'));
Router::connect('/CustomerIO/Broadcasts/listErrorsFromBroadcast', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'listErrorsFromBroadcast'));
Router::connect('/CustomerIO/Broadcasts/listBroadcasts', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'listBroadcasts'));
Router::connect('/CustomerIO/Broadcasts/getBroadcast', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getBroadcast'));
Router::connect('/CustomerIO/Broadcasts/getMetricsForBroadcast', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getMetricsForBroadcast'));
Router::connect('/CustomerIO/Broadcasts/getBroadcastLinkMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getBroadcastLinkMetrics'));
Router::connect('/CustomerIO/Broadcasts/listBroadcastActions', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'listBroadcastActions'));
Router::connect('/CustomerIO/Broadcasts/getMessageMetadataForBroadcast', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getMessageMetadataForBroadcast'));
Router::connect('/CustomerIO/Broadcasts/getBroadcastAction', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getBroadcastAction'));
Router::connect('/CustomerIO/Broadcasts/updateBroadcastAction', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'updateBroadcastAction'));
Router::connect('/CustomerIO/Broadcasts/getBroadcastActionMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getBroadcastActionMetrics'));
Router::connect('/CustomerIO/Broadcasts/getBroadcastActionLinkMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getBroadcastActionLinkMetrics'));
Router::connect('/CustomerIO/Broadcasts/getBroadcastTriggers', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Broadcasts', 'action' => 'getBroadcastTriggers'));
//Campaigns
Router::connect('/CustomerIO/Campaigns/listCampaigns', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'listCampaigns'));
Router::connect('/CustomerIO/Campaigns/getCampaign', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'getCampaign'));
Router::connect('/CustomerIO/Campaigns/getCampaignMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'getCampaignMetrics'));
Router::connect('/CustomerIO/Campaigns/getCampaignLinkMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'getCampaignLinkMetrics'));
Router::connect('/CustomerIO/Campaigns/listCampaignActions', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'listCampaignActions'));
Router::connect('/CustomerIO/Campaigns/getCampaignMessageMetadata', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'getCampaignMessageMetadata'));
Router::connect('/CustomerIO/Campaigns/getCampaignAction', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'getCampaignAction'));
Router::connect('/CustomerIO/Campaigns/updateCampaignAction', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'updateCampaignAction'));
Router::connect('/CustomerIO/Campaigns/getCampaignActionMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'getCampaignActionMetrics'));
Router::connect('/CustomerIO/Campaigns/getLinkMetricsForAction', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Campaigns', 'action' => 'getLinkMetricsForAction'));
//Collections
Router::connect('/CustomerIO/Collections/createCollection', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Collections', 'action' => 'createCollection'));
Router::connect('/CustomerIO/Collections/listCollections', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Collections', 'action' => 'listCollections'));
Router::connect('/CustomerIO/Collections/lookupCollection', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Collections', 'action' => 'lookupCollection'));
Router::connect('/CustomerIO/Collections/deleteCollection', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Collections', 'action' => 'deleteCollection'));
Router::connect('/CustomerIO/Collections/updateCollection', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Collections', 'action' => 'updateCollection'));
Router::connect('/CustomerIO/Collections/lookupCollectionContents', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Collections', 'action' => 'lookupCollectionContents'));
Router::connect('/CustomerIO/Collections/updateContentsOfCollection', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Collections', 'action' => 'updateContentsOfCollection'));
//Events
Router::connect('/CustomerIO/Events/trackCustomerEvent', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Events', 'action' => 'trackCustomerEvent'));
Router::connect('/CustomerIO/Events/trackAnonymousEvent', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Events', 'action' => 'trackAnonymousEvent'));
Router::connect('/CustomerIO/Events/reportPushEvent', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Events', 'action' => 'reportPushEvent'));
//Exports
Router::connect('/CustomerIO/Exports/listExports', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Exports', 'action' => 'listExports'));
Router::connect('/CustomerIO/Exports/getExport', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Exports', 'action' => 'getExport'));
Router::connect('/CustomerIO/Exports/downloadExport', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Exports', 'action' => 'downloadExport'));
Router::connect('/CustomerIO/Exports/exportCustomerData', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Exports', 'action' => 'exportCustomerData'));
Router::connect('/CustomerIO/Exports/exportInfoAboutDeliveries', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Exports', 'action' => 'exportInfoAboutDeliveries'));
//Messages
Router::connect('/CustomerIO/Messages/sendTransactionalEmail', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'sendTransactionalEmail'));
Router::connect('/CustomerIO/Messages/listMessages', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'listMessages'));
Router::connect('/CustomerIO/Messages/getMessage', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'getMessage'));
Router::connect('/CustomerIO/Messages/getArchivedMessage', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'getArchivedMessage'));
Router::connect('/CustomerIO/Messages/listTransactionalMessages', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'listTransactionalMessages'));
Router::connect('/CustomerIO/Messages/getTransactionalMessage', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'getTransactionalMessage'));
Router::connect('/CustomerIO/Messages/getTransactionalMessageMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'getTransactionalMessageMetrics'));
Router::connect('/CustomerIO/Messages/getTransactionalMessageLinkMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'getTransactionalMessageLinkMetrics'));
Router::connect('/CustomerIO/Messages/getTransactionalMessageDeliveries', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Messages', 'action' => 'getTransactionalMessageDeliveries'));
//Newsletters
Router::connect('/CustomerIO/Newsletters/listNewsletters', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'listNewsletters'));
Router::connect('/CustomerIO/Newsletters/getNewsletter', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'getNewsletter'));
Router::connect('/CustomerIO/Newsletters/getNewsletterMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'getNewsletterMetrics'));
Router::connect('/CustomerIO/Newsletters/getNewsletterLinkMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'getNewsletterLinkMetrics'));
Router::connect('/CustomerIO/Newsletters/listNewsletterVariants', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'listNewsletterVariants'));
Router::connect('/CustomerIO/Newsletters/getNewsletterMessageMetadata', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'getNewsletterMessageMetadata'));
Router::connect('/CustomerIO/Newsletters/getNewsletterVariant', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'getNewsletterVariant'));
Router::connect('/CustomerIO/Newsletters/updateNewsletterVariant', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'updateNewsletterVariant'));
Router::connect('/CustomerIO/Newsletters/getMetricsForVariant', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'getMetricsForVariant'));
Router::connect('/CustomerIO/Newsletters/getNewsletterVariantLinkMetrics', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Newsletters', 'action' => 'getNewsletterVariantLinkMetrics'));
//Segments
Router::connect('/CustomerIO/Segments/addPeopleToManualSegment', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'addPeopleToManualSegment'));
Router::connect('/CustomerIO/Segments/removePeopleFromManualSegment', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'removePeopleFromManualSegment'));
Router::connect('/CustomerIO/Segments/createManualSegment', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'createManualSegment'));
Router::connect('/CustomerIO/Segments/listSegments', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'listSegments'));
Router::connect('/CustomerIO/Segments/getSegment', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'getSegment'));
Router::connect('/CustomerIO/Segments/deleteSegment', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'deleteSegment'));
Router::connect('/CustomerIO/Segments/getSegmentDependencies', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'getSegmentDependencies'));
Router::connect('/CustomerIO/Segments/getSegmentCustomerCount', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'getSegmentCustomerCount'));
Router::connect('/CustomerIO/Segments/listCustomersInSegment', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Segments', 'action' => 'listCustomersInSegment'));
//SenderIdentities
Router::connect('/CustomerIO/SenderIdentities/listSenderIdentities', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'SenderIdentities', 'action' => 'listSenderIdentities'));
Router::connect('/CustomerIO/SenderIdentities/getSender', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'SenderIdentities', 'action' => 'getSender'));
Router::connect('/CustomerIO/SenderIdentities/getSenderUsageData', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'SenderIdentities', 'action' => 'getSenderUsageData'));
//Snippets
Router::connect('/CustomerIO/Snippets/listSnippets', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Snippets', 'action' => 'listSnippets'));
Router::connect('/CustomerIO/Snippets/updateSnippets', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Snippets', 'action' => 'updateSnippets'));
Router::connect('/CustomerIO/Snippets/deleteSnippet', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Snippets', 'action' => 'deleteSnippet'));
//WebHooks
Router::connect('/CustomerIO/Webhooks/reportingWebhook', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Webhooks', 'action' => 'reportingWebhook'));
Router::connect('/CustomerIO/Webhooks/listReportingWebhooks', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Webhooks', 'action' => 'listReportingWebhooks'));
Router::connect('/CustomerIO/Webhooks/getReportingWebhook', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Webhooks', 'action' => 'getReportingWebhook'));
Router::connect('/CustomerIO/Webhooks/updateWebhookConfig', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Webhooks', 'action' => 'updateWebhookConfig'));
Router::connect('/CustomerIO/Webhooks/deleteReportingWebhook', array('prefix' => NULL, 'plugin' => 'CustomerIO', 'controller' => 'Webhooks', 'action' => 'deleteReportingWebhook'));


//RAVENTRACK
Router::connect('/Raventrack/Raventrack/getPlayerRegistrations/*', array('prefix' => NULL, 'plugin' => 'Raventrack', 'controller' => 'Raventrack', 'action' => 'getPlayerRegistrations'));
Router::connect('/Raventrack/Raventrack/getStatisticsReport/*', array('prefix' => NULL, 'plugin' => 'Raventrack', 'controller' => 'Raventrack', 'action' => 'getStatisticsReport'));

//Sportsbook
Router::connect('/sportsbook/live', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'live'));
Router::connect('/sportsbook/prematch', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'prematch'));
Router::connect('/sportsbook/greyhound', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'greyhound'));
Router::connect('/sportsbook/keno', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'keno'));
Router::connect('/sportsbook/luckysix', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'luckysix'));
Router::connect('/sportsbook/nextsix', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'nextsix'));
Router::connect('/sportsbook/roulette', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'roulette'));
Router::connect('/sportsbook/vhorse', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'vhorse'));
Router::connect('/sportsbook/vms', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'vms'));
Router::connect('/sportsbook/vps', array('plugin' => 'Sportsbook', 'controller' => 'Sportsbook', 'action' => 'vps'));



/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
