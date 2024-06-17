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
</div>

<div class="mobile-filters">
  <button id="mapView">
    <img src="/wp-content/plugins/irec-green-connect/public/img/map-icon.png" />
    Map View</button>
  <button id="listView" class="hide">
    <img src="/wp-content/plugins/irec-green-connect/public/img/list-view.png" />
    List View</button>
  <div class="mobile-filters__divider"></div>
  <button>
    <img src="/wp-content/plugins/irec-green-connect/public/img/filters-icon.png" />
    Filters</button>
</div>
</div>