[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/GooglePlaces/functions?utm_source=RapidAPIGitHub_GooglePlacesFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)

# GooglePlacesAPI Package
Add location awareness for more contextual results.
* Domain: google.com
* Credentials: apiKey

## How to get credentials: 
0. Go to [Google Developers Console](https://console.developers.google.com/?authuser=1)
1. Select a project, or create a new one
2. Press **Continue** to activate API key
3. Select 'Enable APIs and services' to enable the APIs you want to use
4. In the sidebar on the left, select **Credentials**
5. If your project has no API key for the server, create it now - **Create credentials > API key**

## Custom datatypes: 
 |Datatype|Description|Example
 |--------|-----------|----------
 |Datepicker|String which includes date and time|```2016-05-28 00:00:00```
 |Map|String which includes latitude and longitude coma separated|```50.37, 26.56```
 |List|Simple array|```["123", "sample"]``` 
 |Select|String with predefined values|```sample```
 |Array|Array of objects|```[{"Second name":"123","Age":"12","Photo":"sdf","Draft":"sdfsdf"},{"name":"adi","Second name":"bla","Age":"4","Photo":"asfserwe","Draft":"sdfsdf"}] ```
 


**getNearbyPlaces**
-------

Search for places within a specified area. You can refine your search request by supplying keywords or specifying the type of place you are searching for. 

| Field                  | Type      | Description   |
| -------                | ----      | ---           |
| `apiKey`              | credentials    |  The api key obtained from Google Developers Console.    |
| `coordinate`             | Map    |  The latitude nad longitude of place coma separated.|
| `radius`               | string    |  Distance in meters over which the results must be found. The maximum allowable range is 50 000 meters. Note that the radius parameter should not be used if the value rank_by = distance |
| `rank_by (optional)`   | Select    |  The order of the results. The possible values are: **prominence** (the default) - the results are sorted by popularity. Priority is given to the ranking of the more famous sites located in a given area. At a certain place affect the rating in the Google index, the overall popularity and other factors. **distance** - the results are sorted in order of proximity to the point specified in the parameter location. If you specify distance, you must also specify one or more parameters keyword, name, or type. |
| `keyword (optional)`   | string    |  The word on which the search is conducted in all contents are indexed by Google for a given place, including the name, type, address, user reviews and third-party content. |
| `language (optional)`  | string    |  The language code, which should be possible to return the results. See the [List of supported languages and their codes](https://developers.google.com/maps/faq?authuser=1#languagesupport). |
| `minimum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `maximum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `name (optional)`      | string    |  The space-separated words that are being sought in the names of places. |
| `open_now (optional)`  | string    |  Limit search results to only those organizations (establishments) that are open at the time of sending the request. |
| `type (optional)`      | List    |  Limit search results to only those places whose type corresponds to at least one of these. Types should be separated by a vertical bar (type1 \| type2 \| etc). See the [List of supported types](https://developers.google.com/places/supported_types?authuser=1) |


**searchPlacesByText**
-------

The service responds with a list of places matching the text string and any location bias that has been set.

| Field                  | Type      | Description   |
| -------                | ----      | ---           |
| `apiKey`              | credentials    |  The api key obtained from Google Developers Console.    |
| `query`                | string    |  The phrase that is searched for, such as "restaurant".  |
| `coordinate`             | Map    |  The latitude nad longitude of place coma separated.|
| `radius (optional)`    | string    |  Distance in meters over which the results must be found. The maximum allowable range is 50 000 meters. Note that the radius parameter should not be used if the value rank_by = distance |
| `language (optional)`  | string    |  The language code, which should be possible to return the results. See the [List of supported languages and their codes](https://developers.google.com/maps/faq?authuser=1#languagesupport). |
| `minimum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `maximum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `open_now (optional)`  | string    |  Limit search results to only those organizations (establishments) that are open at the time of sending the request. |
| `type (optional)`      | List    |  Limit search results to only those places whose type corresponds to at least one of these. Types should be separated by a vertical bar (type1 \| type2 \| etc). See the [List of supported types](https://developers.google.com/places/supported_types?authuser=1) |


**getNearbyPlacesRadar**
-------

Search for up to 200 places at once, but with less detail than is typically returned from a Text Search or Nearby Search request.

| Field                  | Type      | Description   |
| -------                | ----      | ---           |
| `apiKey`              | credentials    |  The api key obtained from Google Developers Console.    |
| `coordinate`             | Map    |  The latitude nad longitude of place coma separated.|
| `radius`               | string    |  Distance in meters over which the results must be found. Note that radius must not be included if rankby=distance. The maximum allowable range is 50 000 meters. Note that the radius parameter should not be used if the value rank_by = distance |
| `keyword `   | string    |  The word on which the search is conducted in all contents are indexed by Google for a given place, including the name, type, address, user reviews and third-party content. |
| `name (optional)`      | string    |  The space-separated words that are being sought in the names of places. |
| `language (optional)`  | string    |  The language code, which should be possible to return the results. See the [List of supported languages and their codes](https://developers.google.com/maps/faq?authuser=1#languagesupport). |
| `maximum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `minimum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `open_now (optional)`  | string    |  Limit search results to only those organizations (establishments) that are open at the time of sending the request. |
| `type (optional)`      | List    |  Limit search results to only those places whose type corresponds to at least one of these. Types should be in array. See the [List of supported types](https://developers.google.com/places/supported_types?authuser=1) |

**getNearbyPlacesByName**
-------

Search for up to 200 places at once, but with less detail than is typically returned from a Text Search or Nearby Search request.

| Field                  | Type      | Description   |
| -------                | ----      | ---           |
| `apiKey`              | credentials    |  The api key obtained from Google Developers Console.    |
| `coordinate`             | Map    |  The latitude nad longitude of place coma separated.|
| `radius`               | string    |  Distance in meters over which the results must be found. Note that radius must not be included if rankby=distance. The maximum allowable range is 50 000 meters. Note that the radius parameter should not be used if the value rank_by = distance |
| `keyword (optional)`   | string    |  The word on which the search is conducted in all contents are indexed by Google for a given place, including the name, type, address, user reviews and third-party content. |
| `name `      | string    |  The space-separated words that are being sought in the names of places. |
| `language (optional)`  | string    |  The language code, which should be possible to return the results. See the [List of supported languages and their codes](https://developers.google.com/maps/faq?authuser=1#languagesupport). |
| `maximum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `minimum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `open_now (optional)`  | string    |  Limit search results to only those organizations (establishments) that are open at the time of sending the request. |
| `type (optional)`      | List    |  Limit search results to only those places whose type corresponds to at least one of these. Types should be in array. See the [List of supported types](https://developers.google.com/places/supported_types?authuser=1) |

**getNearbyPlacesByType**
-------

Search for up to 200 places at once, but with less detail than is typically returned from a Text Search or Nearby Search request.

| Field                  | Type      | Description   |
| -------                | ----      | ---           |
| `apiKey`              | credentials    |  The api key obtained from Google Developers Console.    |
| `coordinate`             | Map    |  The latitude nad longitude of place coma separated.|
| `radius`               | string    |  Distance in meters over which the results must be found. Note that radius must not be included if rankby=distance. The maximum allowable range is 50 000 meters. Note that the radius parameter should not be used if the value rank_by = distance |
| `keyword (optional)`   | string    |  The word on which the search is conducted in all contents are indexed by Google for a given place, including the name, type, address, user reviews and third-party content. |
| `name (optional)`      | string    |  The space-separated words that are being sought in the names of places. |
| `language (optional)`  | string    |  The language code, which should be possible to return the results. See the [List of supported languages and their codes](https://developers.google.com/maps/faq?authuser=1#languagesupport). |
| `maximum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `minimum_price (optional)`  | Select    |  The price range, limiting the search results. Valid entries are from 0 (free) to 4 (most expensive). |
| `open_now (optional)`  | string    |  Limit search results to only those organizations (establishments) that are open at the time of sending the request. |
| `type `      | List    |  Limit search results to only those places whose type corresponds to at least one of these. Types should be in array. See the [List of supported types](https://developers.google.com/places/supported_types?authuser=1) |


**getPlaceDetails**
-------

Request more details about a particular establishment or point of interest.

| Field                  | Type      | Description   |
| -------                | ----      | ---           |
| `apiKey`              | credentials    |  The api key obtained from Google Developers Console.    |
| `place_id`             | string    |  The unique text identifier, returned by the **getNearbyPlaces** method.   |


**getImageURL**
-------

Get the image URL for an image of a place.

| Field                  | Type      | Description   |
| -------                | ----      | ---           |
| `apiKey`              | credentials    |  The api key obtained from Google Developers Console.    |
| `image_id`             | string    |  The unique text identifier "photo_reference", returned by the **getNearbyPlaces** method.   |
| `max_width`            | string    |  Indicate the values in pixels for the maximum width of the returned image.   |
| `max_height`           | string    |  Indicate the values in pixels for the maximum height of the returned image.  |


**addPlace**
-------

Add a new place to Google Maps. The new place is available immediately in Nearby Searches initiated by your application. The new place also enters a moderation queue to be considered for Google Maps. A newly-added place does not appear in Text Search or Radar Search results, or to other applications, until it has been approved by the moderation process.

| Field                  | Type      | Description   |
| -------                | ----      | ---           |
| `apiKey`              | credentials    |  The api key obtained from Google Developers Console.    |
| `accuracy`             | number    |  The accuracy of the location of the signal (in meters), which is based on the request. |
| `address (optional)`   | string    |  The address place to be added. |
| `language`             | string    |  The language in which the title is transferred to the place. See the [List of supported languages and their codes](https://developers.google.com/maps/faq#languagesupport). |
| `coordinate`             | Map    |  The latitude nad longitude of place coma separated.|
| `name`                 | string    |  The full name of the place. No more than 255 characters. |
| `phoneNumber (optional)` | string    |  The phone number of the place. |
| `types`                | string    |  The category to which this place. See [supported types](https://developers.google.com/places/supported_types) |
| `website (optional)`   | string    |  The URL-address of the official web site of the place, such as home organization page. |
