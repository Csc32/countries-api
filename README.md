# Countries API

This **_API_** was created using Laravel and Test Driven Development (TDD) for learning. Currently It has the following **_EndPoints_**

## EndPoints for countries:

-   **GET: /api/Countries/{country_id}**

**_Test Cases_**:

1.  [x] should return 404 when not records
1.  [x] should return array of all records
1.  [x] param country_id should be a number
1.  [x] should return a specify country by id

-   **GET: /api/Countries/getStates/{country_id}**

**_Test Cases_**:

1.  [x] should return all states of a country
1.  [x] should return bad request if the states doesn't exists
1.  [x] should return all states of a country

- **GET: /api/countries/{country_id}/countStates**
**_Test Cases_**:

1.  [ ] should return total states of a country 

-   **POST: /api/Countries/store/**

**_Test Cases_**:

- [x]  should return bad request if there is not data of the country
- [x]  should return bad request when there is invalid name for insert
- [x]  should return bad request when there is invalid population for insert
- [x]  should insert data if there is valid params of the country

-   **PUT: /api/Countries/{country_id}**

**_Test Cases_**:

1.  [x] should return bad request if there is not data for update the country
1.  [x] should return a status 404 and an error message if the param is not a valid country_id
1.  [x] should return bad request and error message if inputs are invalids
1.  [x] should return a message when data has been updated

-   **DELETE: /api/Countries/{country_id}**

**_Test Cases_**:

1. [x]  should return bad request and error message if the id is invalid
1. [x]  should return bad request if there is not data of the country
1. [x]  should return a message when data has been deleted
## EndPoints for States:

-   **GET: /api/states/{state_id}**

**_Test cases_**:

1.  [x] should return 404 if there is not states
1.  [x] should return bad request if there is an invalid param 
1.  [x] should return all states and status 200 
1.  [x] should return information if state exist 

-   **POST: /api/states/store**

**_Test cases_**:

1.  [x] should return bad request if not a valid param
1.  [x] params should not have id 
1.  [x] name is a string
1.  [x] population is integer
1.  [x] country id belongs to a valid country
1.  [x] should return a success message if data was inserted

-   **PUT: /api/states/{state_id}**

**_Test cases_**:

1.  [x] id is a valid integer 
1.  [x] id belongs to a valid state
1.  [x] name is a string
1.  [x] population is integer
1.  [x] country id belongs to a valid country
1.  [x] update a valid state

-   **DELETE: /api/states/{state_id}**

**_Test cases_**:

1.  [x]  should return bad request if not a valid param
1.  [x]  should return not found if the id doesn’t exists
1.  [x]  should return a success message if data deleted correctly

## EndPoints for municipalities:

-   **GET: /api/municipalities/{state_id}**

**_Test cases_**:

1.  [ ] should return 404 if there is not municipalities
1.  [ ] should return bad request if there is an invalid param _{state_id}_
1.  [ ] should return data of all states and status 200
1.  [ ] should return all states of the country

