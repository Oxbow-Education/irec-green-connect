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

class AppsPeopleOzExternalMergedpeopleapiAffinity extends \Google\Model
{
  /**
   * @var SocialGraphWireProtoPeopleapiAffinityMetadata
   */
  public $affinityMetadata;
  protected $affinityMetadataType = SocialGraphWireProtoPeopleapiAffinityMetadata::class;
  protected $affinityMetadataDataType = '';
  /**
   * @var string
   */
  public $affinityType;
  /**
   * @var string
   */
  public $containerId;
  /**
   * @var string
   */
  public $containerType;
  /**
   * @var string
   */
  public $loggingId;
  public $value;

  /**
   * @param SocialGraphWireProtoPeopleapiAffinityMetadata
   */
  public function setAffinityMetadata(SocialGraphWireProtoPeopleapiAffinityMetadata $affinityMetadata)
  {
    $this->affinityMetadata = $affinityMetadata;
  }
  /**
   * @return SocialGraphWireProtoPeopleapiAffinityMetadata
   */
  public function getAffinityMetadata()
  {
    return $this->affinityMetadata;
  }
  /**
   * @param string
   */
  public function setAffinityType($affinityType)
  {
    $this->affinityType = $affinityType;
  }
  /**
   * @return string
   */
  public function getAffinityType()
  {
    return $this->affinityType;
  }
  /**
   * @param string
   */
  public function setContainerId($containerId)
  {
    $this->containerId = $containerId;
  }
  /**
   * @return string
   */
  public function getContainerId()
  {
    return $this->containerId;
  }
  /**
   * @param string
   */
  public function setContainerType($containerType)
  {
    $this->containerType = $containerType;
  }
  /**
   * @return string
   */
  public function getContainerType()
  {
    return $this->containerType;
  }
  /**
   * @param string
   */
  public function setLoggingId($loggingId)
  {
    $this->loggingId = $loggingId;
  }
  /**
   * @return string
   */
  public function getLoggingId()
  {
    return $this->loggingId;
  }
  public function setValue($value)
  {
    $this->value = $value;
  }
  public function getValue()
  {
    return $this->value;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AppsPeopleOzExternalMergedpeopleapiAffinity::class, 'Google_Service_Contentwarehouse_AppsPeopleOzExternalMergedpeopleapiAffinity');
