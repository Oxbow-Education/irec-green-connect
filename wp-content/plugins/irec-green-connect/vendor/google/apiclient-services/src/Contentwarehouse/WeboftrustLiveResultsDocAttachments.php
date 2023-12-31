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

class WeboftrustLiveResultsDocAttachments extends \Google\Collection
{
  protected $collection_key = 'providerAttachment';
  /**
   * @var WeboftrustLiveResultDocBoostData[]
   */
  public $docBoost;
  protected $docBoostType = WeboftrustLiveResultDocBoostData::class;
  protected $docBoostDataType = 'array';
  /**
   * @var WeboftrustLiveResultProviderDocAttachment[]
   */
  public $providerAttachment;
  protected $providerAttachmentType = WeboftrustLiveResultProviderDocAttachment::class;
  protected $providerAttachmentDataType = 'array';

  /**
   * @param WeboftrustLiveResultDocBoostData[]
   */
  public function setDocBoost($docBoost)
  {
    $this->docBoost = $docBoost;
  }
  /**
   * @return WeboftrustLiveResultDocBoostData[]
   */
  public function getDocBoost()
  {
    return $this->docBoost;
  }
  /**
   * @param WeboftrustLiveResultProviderDocAttachment[]
   */
  public function setProviderAttachment($providerAttachment)
  {
    $this->providerAttachment = $providerAttachment;
  }
  /**
   * @return WeboftrustLiveResultProviderDocAttachment[]
   */
  public function getProviderAttachment()
  {
    return $this->providerAttachment;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(WeboftrustLiveResultsDocAttachments::class, 'Google_Service_Contentwarehouse_WeboftrustLiveResultsDocAttachments');
