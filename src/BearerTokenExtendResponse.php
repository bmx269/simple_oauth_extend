<?php

namespace Drupal\simple_oauth_extend;

use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class BearerTokenExtendResponse extends BearerTokenResponse {

  /**
   * Add custom fields to your Bearer Token response here, then override
   * AuthorizationServer::getResponseType() to pull in your version of
   * this class rather than the default.
   *
   * @param AccessTokenEntityInterface $accessToken
   *
   * @return array
   */
  protected function getExtraParams(AccessTokenEntityInterface $accessToken)
  {
    $uid = (int)$accessToken->getUserIdentifier();
    $user = \Drupal\user\Entity\User::load($uid);

    // Check if user has a photo, if not, use a default one.
    if (!$user->user_picture->isEmpty()) {
      $displayImg = \Drupal::service('file_url_generator')->generateAbsoluteString($user->user_picture->entity->getFileUri());
    }else{
      $displayImg = 'https://img.icons8.com/external-flaticons-flat-flat-icons/64/undefined/external-user-web-flaticons-flat-flat-icons-2.png';    
    } 

    return [
      // Add custom fields to your Bearer Token response here.
        'user_id' => $uid,
        'username' => $user->getDisplayName(),
        'email' => $user->getEmail(),
        'displayImg' => $displayImg,
        'lastLoggedIn' => $user->getLastAccessedTime(),
        
    ];
  }
}