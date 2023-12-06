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

namespace Google\Service\ChecksService;

class GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidence extends \Google\Collection
{
  protected $collection_key = 'sdkDetails';
  /**
   * @var GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidenceSdkDetails[]
   */
  public $sdkDetails;
  protected $sdkDetailsType = GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidenceSdkDetails::class;
  protected $sdkDetailsDataType = 'array';

  /**
   * @param GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidenceSdkDetails[]
   */
  public function setSdkDetails($sdkDetails)
  {
    $this->sdkDetails = $sdkDetails;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidenceSdkDetails[]
   */
  public function getSdkDetails()
  {
    return $this->sdkDetails;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidence::class, 'Google_Service_ChecksService_GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidence');
