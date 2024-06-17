<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/shoelace-autoloader.js"></script>

<div class="connect-now">
  <div class="connect-now__header">
    <div class="connect-now__left">
      <h1>Connect Now </h1>
      <div class="location">
        <form class="location__form">
          <input class="location__input" type="text" placeholder="Enter your zipcode">
          <button class="location__current"><img src="/wp-content/plugins/irec-green-connect/public/img/crosshairs-regular.svg" alt="Use current location" /></button>
          <button type="submit" class="location__submit">Go</button>
        </form>
        <div class="location__remote">
          <label for="includeRemote">
            <span>
              Include Remote
            </span>
            <div class="switch">
              <input id="includeRemote" type="checkbox" class="switch__input">
              <span class="switch__slider"></span>
            </div>
          </label>
        </div>
      </div>
    </div>
    <div class="filters">
      <div class="opportunity">
        <select class="opportunity__select" name="opportunity" id="opportunity">
          <option value="" disabled selected>Opportunity</option>
        </select>
        <span class="select__arrow"><img src="/wp-content/plugins/irec-green-connect/public/img/caret-down-solid.svg" alt=""></span>
      </div>
      <div class="more-filters">
        <button class="more-filters__button">
          <span>
            More Filters
          </span>
          <img class="more-filters__icon" src="/wp-content/plugins/irec-green-connect/public/img/more-filters.svg" alt="">
        </button>

        <div class="more-filters__container"></div>
      </div>
      <form id="algoliaSearch" class="search">
        <input class="search__input" name="query" required type="text" placeholder="Search by Program or Oragnization">
        <button type="button" class="search__clear"><img src="/wp-content/plugins/irec-green-connect/public/img/times-2.png" alt="clear search"></button>
        <button type="submit" class="search__icon"><img src="/wp-content/plugins/irec-green-connect/public/img/magnifying-glass.svg" alt="submit search"></button>
      </form>
    </div>
  </div>

  <div class="connect-now__main">
    <div class="results">
      <div class="results__meta">

        <h2>Organizations</h2>
      </div>

      <div class="results__hits"></div>
    </div>
    <div id="map" class="map"></div>
  </div>

  <div class="mobile-filters">
    <button id="mapView">
      <img src="/wp-content/plugins/irec-green-connect/public/img/map-icon.png" />
      Map View</button>
    <button id="listView" class="hide">
      <img src="/wp-content/plugins/irec-green-connect/public/img/list-view.png" />
      List View</button>
    <div class="mobile-filters__divider"></div>
    <button id="drawerButton">
      <img src="/wp-content/plugins/irec-green-connect/public/img/filters-icon.png" />
      Filters</button>
  </div>
  <sl-drawer id="drawer" label="Filter your results" placement="bottom" style="--size: 90vh;" class="drawer-placement-bottom">
    <sl-switch style="--height: 23.81px; --width: 35.72px;" class="switch">Include Remote</sl-switch>

    <h3>Opportunity</h3>
    <sl-checkbox>Hiring</sl-checkbox>
    <p>Get hired for a job or apprenticeship. </p>
    <sl-checkbox>Training</sl-checkbox>
    <p>Get training for a career role. </p>
    <sl-checkbox>Information</sl-checkbox>
    <p>Learn more about the energy workforce. </p>
    <sl-checkbox>Bids & Contracts</sl-checkbox>
    <p>If you’re a contractor, you can find potential customers.</p>
    <sl-checkbox>Create an Apprenticeship Program</sl-checkbox>
    <p>If you’re an employer, you can find potential partners.</p>
    <h3>Tags</h3>
    <button>Community Partner</button>
    <button>Electric Vehicles & Battery Storage</button>
    <button>Energy Efficiency</button>
    <button>Registered Apprenticeship</button>
    <button>Solar Energy</button>
    <button>Wind Energy</button>
    <button>Training Provider</button>
    <button>Weatherization Assistance Program Employer</button>
    <button>Youth Program</button>
    <h3>Careers</h3>
    <form id="algoliaSearch" class="search">
      <input class="search__input" name="query" required type="text" placeholder="Search by Program or Oragnization">
      <button type="button" class="search__clear"><img src="/wp-content/plugins/irec-green-connect/public/img/times-2.png" alt="clear search"></button>
      <button type="submit" class="search__icon"><img src="/wp-content/plugins/irec-green-connect/public/img/magnifying-glass.svg" alt="submit search"></button>
    </form>
    <sl-button slot="footer" variant="primary">Close</sl-button>
  </sl-drawer>

</div>