const URL_UPDATED = 'url-updated';
const ALGOLIA_INITIALIZED = 'algolia-initialized';

function sendEvent(eventName) {
  const event = new Event(eventName);
  window.dispatchEvent(event);
}
function updateQueryParam(
  key,
  value,
  removeValue = false,
  single = false,
  dontSend = false,
) {
  const url = new URL(window.location);
  const currentValue = url.searchParams.get(key);

  if (single) {
    if (removeValue) {
      // If removeValue is true and single is true, simply delete the parameter
      url.searchParams.delete(key);
    } else {
      // If single is true, replace the existing value with the new one
      url.searchParams.set(key, value);
    }
  } else {
    // Handling as an array of values
    if (currentValue) {
      let values = currentValue.split(',');

      if (removeValue) {
        // Remove the specified value from the array
        values = values.filter((v) => v !== value);
      } else {
        // Add the value if it's not already in the array
        if (!values.includes(value)) {
          values.push(value);
        }
      }

      // Update the parameter with the new array, joined by commas, or delete if empty
      if (values.length > 0) {
        url.searchParams.set(key, values.join(','));
      } else {
        url.searchParams.delete(key);
      }
    } else if (!removeValue) {
      // If the parameter does not exist and we are not removing, set the new value
      url.searchParams.set(key, value);
    }
  }

  // Update the URL in the browser without reloading the page
  window.history.replaceState({ path: url.toString() }, '', url.toString());
  if (dontSend) return;
  sendEvent(URL_UPDATED);
}
