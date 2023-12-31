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

namespace Google\Service\OSConfig;

class OSPolicyResourceGroup extends \Google\Collection
{
  protected $collection_key = 'resources';
  /**
   * @var OSPolicyInventoryFilter[]
   */
  public $inventoryFilters;
  protected $inventoryFiltersType = OSPolicyInventoryFilter::class;
  protected $inventoryFiltersDataType = 'array';
  /**
   * @var OSPolicyResource[]
   */
  public $resources;
  protected $resourcesType = OSPolicyResource::class;
  protected $resourcesDataType = 'array';

  /**
   * @param OSPolicyInventoryFilter[]
   */
  public function setInventoryFilters($inventoryFilters)
  {
    $this->inventoryFilters = $inventoryFilters;
  }
  /**
   * @return OSPolicyInventoryFilter[]
   */
  public function getInventoryFilters()
  {
    return $this->inventoryFilters;
  }
  /**
   * @param OSPolicyResource[]
   */
  public function setResources($resources)
  {
    $this->resources = $resources;
  }
  /**
   * @return OSPolicyResource[]
   */
  public function getResources()
  {
    return $this->resources;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OSPolicyResourceGroup::class, 'Google_Service_OSConfig_OSPolicyResourceGroup');
