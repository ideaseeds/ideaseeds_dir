<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(8, new lime_output_color());

$table = Doctrine::getTable('OAuthConsumerInformation');
$oauthConsumer1 = $table->findOneByName('test1');
$oauthConsumer2 = $table->findOneByName('test2');
$newOAuthConsumer = new OAuthConsumerInformation();

//------------------------------------------------------------
$t->diag('OAuthConsumerInformation');
$t->diag('OAuthConsumerInformation::preSave()');
$newOAuthConsumer->save();
$t->is(strlen($newOAuthConsumer->getKeyString()), 16);
$t->is(strlen($newOAuthConsumer->getSecret()), 32);

//------------------------------------------------------------
$t->diag('OAuthConsumerInformation::getAPICaptions()');
$result = $oauthConsumer1->getAPICaptions();
$t->is($result, array(0 => 'メンバー情報の取得'));

//------------------------------------------------------------
$t->diag('OAuthConsumerInformation::getOAuthAdminAccessToken()');
$result = $oauthConsumer1->getOAuthAdminAccessToken();
$t->isa_ok($result, 'OAuthAdminToken');
$result = $oauthConsumer2->getOAuthAdminAccessToken();
$t->ok(!$result);

//------------------------------------------------------------
$t->diag('OAuthConsumerInformation::getOAuthMemberAccessToken()');
$result = $oauthConsumer1->getOAuthMemberAccessToken(1);
$t->ok(!$result);
$result = $oauthConsumer2->getOAuthMemberAccessToken(1);
$t->isa_ok($result, 'OAuthMemberToken');

//------------------------------------------------------------
$t->diag('OAuthConsumerInformation::getImageFileName()');
$t->is($oauthConsumer1->getImageFileName(), 'dummy_file');
