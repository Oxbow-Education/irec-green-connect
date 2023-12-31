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

namespace Google\Service\Datapipelines;

class GoogleCloudDatapipelinesV1LaunchTemplateRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $gcsPath;
  /**
   * @var GoogleCloudDatapipelinesV1LaunchTemplateParameters
   */
  public $launchParameters;
  protected $launchParametersType = GoogleCloudDatapipelinesV1LaunchTemplateParameters::class;
  protected $launchParametersDataType = '';
  /**
   * @var string
   */
  public $location;
  /**
   * @var string
   */
  public $projectId;
  /**
   * @var bool
   */
  public $validateOnly;

  /**
   * @param string
   */
  public function setGcsPath($gcsPath)
  {
    $this->gcsPath = $gcsPath;
  }
  /**
   * @return string
   */
  public function getGcsPath()
  {
    return $this->gcsPath;
  }
  /**
   * @param GoogleCloudDatapipelinesV1LaunchTemplateParameters
   */
  public function setLaunchParameters(GoogleCloudDatapipelinesV1LaunchTemplateParameters $launchParameters)
  {
    $this->launchParameters = $launchParameters;
  }
  /**
   * @return GoogleCloudDatapipelinesV1LaunchTemplateParameters
   */
  public function getLaunchParameters()
  {
    return $this->launchParameters;
  }
  /**
   * @param string
   */
  public function setLocation($location)
  {
    $this->location = $location;
  }
  /**
   * @return string
   */
  public function getLocation()
  {
    return $this->location;
  }
  /**
   * @param string
   */
  public function setProjectId($projectId)
  {
    $this->projectId = $projectId;
  }
  /**
   * @return string
   */
  public function getProjectId()
  {
    return $this->projectId;
  }
  /**
   * @param bool
   */
  public function setValidateOnly($validateOnly)
  {
    $this->validateOnly = $validateOnly;
  }
  /**
   * @return bool
   */
  public function getValidateOnly()
  {
    return $this->validateOnly;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatapipelinesV1LaunchTemplateRequest::class, 'Google_Service_Datapipelines_GoogleCloudDatapipelinesV1LaunchTemplateRequest');
