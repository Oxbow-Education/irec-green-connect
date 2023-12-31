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

namespace Google\Service\Apigee;

class GoogleCloudApigeeV1ListDeveloperSubscriptionsResponse extends \Google\Collection
{
  protected $collection_key = 'developerSubscriptions';
  /**
   * @var GoogleCloudApigeeV1DeveloperSubscription[]
   */
  public $developerSubscriptions;
  protected $developerSubscriptionsType = GoogleCloudApigeeV1DeveloperSubscription::class;
  protected $developerSubscriptionsDataType = 'array';
  /**
   * @var string
   */
  public $nextStartKey;

  /**
   * @param GoogleCloudApigeeV1DeveloperSubscription[]
   */
  public function setDeveloperSubscriptions($developerSubscriptions)
  {
    $this->developerSubscriptions = $developerSubscriptions;
  }
  /**
   * @return GoogleCloudApigeeV1DeveloperSubscription[]
   */
  public function getDeveloperSubscriptions()
  {
    return $this->developerSubscriptions;
  }
  /**
   * @param string
   */
  public function setNextStartKey($nextStartKey)
  {
    $this->nextStartKey = $nextStartKey;
  }
  /**
   * @return string
   */
  public function getNextStartKey()
  {
    return $this->nextStartKey;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudApigeeV1ListDeveloperSubscriptionsResponse::class, 'Google_Service_Apigee_GoogleCloudApigeeV1ListDeveloperSubscriptionsResponse');
