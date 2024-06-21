<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/shoelace-autoloader.js"></script>

<div class="connect-now">
  <div class="connect-now__header">
    <div class="connect-now__left">
      <h1>Connect Now </h1>
      <div class="location">
        <form class="location__form">
          <input id="autocomplete" class="location__input" type="text" placeholder="Enter zip, city, or state">
          <button class="location__current"><img src="/wp-content/plugins/irec-green-connect/public/img/crosshairs-regular.svg" alt="Use current location" /></button>
          <button type="submit" class="location__submit">Go</button>
        </form>
        <!-- <div class="location__remote">
          <label>Include Remote
            <sl-switch style="--height: 23.81px; --width: 35.72px;" class="switch"></sl-switch>
          </label>
        </div> -->
      </div>
    </div>
    <div class="filters">
      <div class="opportunity">
        <sl-dropdown class="opportunity__dropdown" placement="bottom-center" distance="10">
          <button class="opportunity__trigger" slot="trigger" caret>Opportunity
            <img src="/wp-content/plugins/irec-green-connect/public/img/caret-down-solid.svg" alt="" /> </button>
          <sl-checkbox name="opportunities" value="Hiring" class="checkbox" help-text="Get hired for a job or apprenticeship.">Hiring</sl-checkbox class="checkbox">
          <sl-divider></sl-divider>
          <sl-checkbox name="opportunities" value="Training" class="checkbox" help-text="Get training for a career role.">Training</sl-checkbox class="checkbox">
          <sl-divider></sl-divider>
          <sl-checkbox name="opportunities" value="Information" class="checkbox" help-text="Learn more about the energy workforce.">Information</sl-checkbox class="checkbox">
          <sl-divider></sl-divider>
          <sl-checkbox name="opportunities" value="Bids & Contracts" class="checkbox" help-text="If you’re a contractor, you can find potential customers.">Bids & Contracts</sl-checkbox class="checkbox">
          <sl-divider></sl-divider>
          <sl-checkbox name="opportunities" value="Create an Apprenticeship Program" class="checkbox" help-text="If you’re an employer, you can find potential partners.">Create an Apprenticeship Program</sl-checkbox class="checkbox">
        </sl-dropdown>


      </div>
      <div class="more-filters">
        <div class="tags">
          <sl-dropdown class="opportunity__dropdown" placement="bottom-center" distance="10">
            <button class="opportunity__trigger" slot="trigger" caret>Tags
              <img src="/wp-content/plugins/irec-green-connect/public/img/caret-down-solid.svg" alt="" /> </button>
            </button>
            <div class="more-filters__tags-container">
              <button class="tags__button"> Community Partner</button>
              <button class="tags__button">Electric Vehicles & Battery Storage</button>
              <button class="tags__button">Energy Efficiency</button>
              <button class="tags__button">Registered Apprenticeship</button>
              <button class="tags__button">Solar Energy</button>
              <button class="tags__button">Wind Energy</button>
              <button class="tags__button">Training Provider</button>
              <button class="tags__button tags__button--long">Weatherization Assistance Program Employer</button>
              <button class="tags__button">Youth Program</button>
            </div>

          </sl-dropdown>
        </div>

      </div>
      <form class="search">
        <input class="search__input" name="query" type="text" placeholder="Search by Program or Oragnization">
        <button type="submit" class="search__icon"><img src="/wp-content/plugins/irec-green-connect/public/img/magnifying-glass.svg" alt="submit search"></button>
      </form>
    </div>
  </div>

  <div class="connect-now__main">
    <div class="results">
      <div class="results__meta">

        <h2>Organizations Near Your Location</h2>
        <div id="metaInfo" class="meta__info">
          <p class="results__count"></p>
        </div>
      </div>

      <div id="resultsHits" class="results__hits"></div>
      <div class="results__meta">

        <h2>Remote Organizations</h2>
        <div id="metaInfoRemote" class="meta__info">
          <p class="results__count"></p>
        </div>
      </div>

      <div id="resultsHitsRemote" class="results__hits"></div>
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
    <!-- <sl-switch style="--height: 23.81px; --width: 35.72px;" class="switch">Include Remote</sl-switch> -->

    <h3>Opportunity</h3>
    <div class="mobile-filters__checkbox-container">
      <sl-checkbox name="opportunities" value="Hiring" class="checkbox" help-text="Get hired for a job or apprenticeship.">Hiring</sl-checkbox class="checkbox">
      <sl-divider></sl-divider>
      <sl-checkbox name="opportunities" value="Training" class="checkbox" help-text="Get training for a career role.">Training</sl-checkbox class="checkbox">
      <sl-divider></sl-divider>
      <sl-checkbox name="opportunities" value="Information" class="checkbox" help-text="Learn more about the energy workforce.">Information</sl-checkbox class="checkbox">
      <sl-divider></sl-divider>
      <sl-checkbox name="opportunities" value="Bids & Contracts" class="checkbox" help-text="If you’re a contractor, you can find potential customers.">Bids & Contracts</sl-checkbox class="checkbox">
      <sl-divider></sl-divider>
      <sl-checkbox name="opportunities" value="Create an Apprenticeship Program" class="checkbox" help-text="If you’re an employer, you can find potential partners.">Create an Apprenticeship Program</sl-checkbox class="checkbox">
    </div>
    <h3>Tags</h3>
    <div class="mobile-filters__tags-container">

      <button class="tags__button"> Community Partner</button>
      <button class="tags__button">Electric Vehicles & Battery Storage</button>
      <button class="tags__button">Energy Efficiency</button>
      <button class="tags__button">Registered Apprenticeship</button>
      <button class="tags__button">Solar Energy</button>
      <button class="tags__button">Wind Energy</button>
      <button class="tags__button">Training Provider</button>
      <button class="tags__button">Weatherization Assistance Program Employer</button>
      <button class="tags__button">Youth Program</button>
    </div>


    <div class="more-filters__footer" slot="footer">
      <form slot="footer" class="search">
        <input class="search__input" name="query" type="text" placeholder="Search by Program or Oragnization">
        <button type="submit" class="search__icon"><img src="/wp-content/plugins/irec-green-connect/public/img/magnifying-glass.svg" alt="submit search"></button>
      </form>
      <sl-divider></sl-divider>
      <div class="footer__button-container">
        <button class="footer__reset">Reset All</button>
        <button class="footer__see-results">See X Results</button>
      </div>
    </div>
  </sl-drawer>

</div>