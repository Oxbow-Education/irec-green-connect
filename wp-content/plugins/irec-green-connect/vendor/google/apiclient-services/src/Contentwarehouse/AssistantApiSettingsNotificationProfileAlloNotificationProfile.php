<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Contentwarehouse;

class AssistantApiSettingsNotificationProfileAlloNotificationProfile extends \Google\Model
{
  /**
   * @var ChatBotPlatformBotSendToken
   */
  public $botSendToken;
  protected $botSendTokenType = ChatBotPlatformBotSendToken::class;
  protected $botSendTokenDataType = '';
  /**
   * @var ChatBotPlatformFireballId
   */
  public $id;
  protected $idType = ChatBotPlatformFireballId::class;
  protected $idDataType = '';

  /**
   * @param ChatBotPlatformBotSendToken
   */
  public function setBotSendToken(ChatBotPlatformBotSendToken $botSendToken)
  {
    $this->botSendToken = $botSendToken;
  }
  /**
   * @return ChatBotPlatformBotSendToken
   */
  public function getBotSendToken()
  {
    return $this->botSendToken;
  }
  /**
   * @param ChatBotPlatformFireballId
   */
  public function setId(ChatBotPlatformFireballId $id)
  {
    $this->id = $id;
  }
  /**
   * @return ChatBotPlatformFireballId
   */
  public function getId()
  {
    return $this->id;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AssistantApiSettingsNotificationProfileAlloNotificationProfile::class, 'Google_Service_Contentwarehouse_AssistantApiSettingsNotificationProfileAlloNotificationProfile');
