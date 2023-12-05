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

namespace Google\Service\SA360;

class GoogleAdsSearchads360V0ResourcesLabel extends \Google\Model
{
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $resourceName;
  /**
   * @var string
   */
  public $status;
  /**
   * @var GoogleAdsSearchads360V0CommonTextLabel
   */
  public $textLabel;
  protected $textLabelType = GoogleAdsSearchads360V0CommonTextLabel::class;
  protected $textLabelDataType = '';

  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param string
   */
  public function setResourceName($resourceName)
  {
    $this->resourceName = $resourceName;
  }
  /**
   * @return string
   */
  public function getResourceName()
  {
    return $this->resourceName;
  }
  /**
   * @param string
   */
  public function setStatus($status)
  {
    $this->status = $status;
  }
  /**
   * @return string
   */
  public function getStatus()
  {
    return $this->status;
  }
  /**
   * @param GoogleAdsSearchads360V0CommonTextLabel
   */
  public function setTextLabel(GoogleAdsSearchads360V0CommonTextLabel $textLabel)
  {
    $this->textLabel = $textLabel;
  }
  /**
   * @return GoogleAdsSearchads360V0CommonTextLabel
   */
  public function getTextLabel()
  {
    return $this->textLabel;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleAdsSearchads360V0ResourcesLabel::class, 'Google_Service_SA360_GoogleAdsSearchads360V0ResourcesLabel');
