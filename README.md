# Laravel + Passport Skeleton

## Working from this skeleton

You should fork this repository before working with it.

You'll need [Vagrant](https://vagrantup.com)


Once forked and cloned on your local development machine, run ```composer install```

Whenever you start working on your project : ```vagrant up```

When done : ```vagrant halt```


## What comes bundled with this repository

* Oauth2 server provided by laravel/passport.
* Simple Session API built on top of Oauth2 server
* Basic User model
* Custom composer scripts

## Composer scripts

All scripts must run inside Homestead or production environment:

* ```composer run-script test```  : run unit & feature tests
* ```composer run-script setup``` : setup application, install passport
* ```composer run-script reset``` : reset application, migrate fresh database, run tests

## Automatic Homestead.yaml generation

Outside Homestead, each time you run ```composer install```, Homestead.yaml is
generated automagically and "post-root-package-install" script is run.

## Session API

### POST /api/token

Login user against Oauth2 server.

__Request:__

__Headers:__

* Accept : application/json

__Body:__
```
{
    "email" : " ... USER EMAIL ... ",
    "password" : " ... USER PASSWORD ... "
}
```

__Response:__

__Status Code:__ 200 (OK) | 401 (Unauthorized) | 422 (Unprocessable Entity)

__Body:__

*200 OK*

```
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": " ... ACCESS_TOKEN_STRING ... ",
    "refresh_token": " ... REFRESH_TOKEN_STRING ... "
}
```

*401 Unauthorized*

```
{
    "error" : "invalid_credentials",
    "error_description" : "The user credentials were incorrect",
    "message" : "The user credentials were incorrect"
}
```

### DELETE /api/token

Delete authorization token provided in "Authorization" header, effectively logging out current user.

__Request:__

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 204 (NO CONTENT) or 401 (Unauthorized)

__Body:__

*401 Unauthorized*

```
{
    "error" : "unauthorized"
}
```

### POST /api/token/refresh

Refresh a given authorization token, using Oauth2 server.

__Request:__

__Headers:__

* Accept : application/json
* Content-Type : application/json

__Body:__

```
{
    "refresh_token" : " ... REFRESH_TOKEN_STRING ... "
}
```

__Response:__

__Status Code:__ 200 (OK) or 422 (Unprocessable Entity)

__Body:__

*200 OK*

```
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": " ... ACCESS_TOKEN_STRING ... ",
    "refresh_token": " ... REFRESH_TOKEN_STRING ... "
}
```

### GET /api/user

Return back to caller a JSON representation of current user, given an authorization token.

__Request:__

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK) or 401 (Unauthorized)

__Body:__

*200 OK*

```
{
    "id": Integer,
    "name": String,
    "email": String,
    "email_verified_at": Date,
    "created_at": Date,
    "updated_at": Date
}
```



### POST /api/user

Create an user.

__Request:__

*Body :*
```
{
    "name": String,
    "email": String,
    "password": String,
}
```

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 201 (CREATED), 401 (Unauthorized) or 422 (Unprocessable Entity)

__Body:__

*201 CREATED*

```
{
    "name": String,
    "email": String,
    "email_verified_at": Date,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```

*422 Unprocessable Entity*

```
{
    "message": "The given data was invalid."
}
```



### DELETE /api/user

Delete my user.

__Request:__


__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 204 (NO CONTENT), 401 (Unauthorized) or 422 (Unprocessable Entity)

__Body:__

*204 NO CONTENT*

```
{
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```



### POST /api/ticket

Create a ticket.

__Request:__

*Body :*
```
{
    "title": String,
    "description": String,
    "priority": String in ["basse","normale","haute"],
}
```

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 201 (CREATED), 401 (Unauthorized) or 422 (Unprocessable Entity)

__Body:__

*201 CREATED*

```
{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```

*422 Unprocessable Entity*

```
{
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "The title field is required."
        ],
        "description": [
            "The description field is required."
        ],
        "priority": [
            "The priority field is required."
        ]
    }
}
```



### DELETE /api/ticket/{id}

Delete a ticket.

__Request:__ id: Ticket id.

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 204 (NO CONTENT), 401 (Unauthorized) or 404 (Not Found)

__Body:__

*204 NO CONTENT*

```
"Ticket deleted"
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```

*404 Not Found*

```
{
    "message": "No query results for model [App\\Ticket] {id}"
}
```



### UPDATE /api/ticket/{id}

Update a ticket.

__Request:__ 
*id*: Ticket id
*Body :*
```
{
    "description": String,
    "priority": String in ["basse","normale","haute"],
}
```

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized) or 422 (Unprocessable Entity)

__Body:__

*200 OK*

```
{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```

*422 Unprocessable Entity*

```
{
    "message": "The given data was invalid.",
    "errors": {
        "description": [
            "The description field is required."
        ],
        "priority": [
            "The priority field is required."
        ]
    }
}
```

### PUT /api/ticket/assign/{id}

Assign a ticket.

__Request:__ 
*id*: Ticket id.
*Body :*
```
{
    "user": Integer
}
```

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized) or 422 (Unprocessable Entity)

__Body:__

*200 OK*

```
{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```

*422 Unprocessable Entity*

```
{
    "message": "The given data was invalid."
}
```



### DELETE /api/ticket/assign/{id}

Delete a assign.

__Request:__ 
*id*: Ticket id.

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized) or 422 (Unprocessable Entity)

__Body:__

*200 OK*

```
{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```

*422 Unprocessable Entity*

```
{
    "message": "The given data was invalid."
}
```


### PUT /api/ticket/start/{id}

Start a ticket.

__Request:__ 
*id*: Ticket id.

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized)

__Body:__

*200 OK*

```
{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```



### PUT /api/ticket/finish/{id}

Finish a ticket.

__Request:__ 
*id*: Ticket id.

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized)

__Body:__

*200 OK*

```
{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```



### POST /api/ticket/comment/{id}

Comment a ticket.

__Request:__ 
*id*: Ticket id.
*Body :*
```
{
    "text": String
}
```
__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized) or 422 (Unprocessable Entity)

__Body:__

*200 OK*

```
{
    "text": String,
    "id_ticket": Integer,
    "author": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "ticket": Ticket
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```

*422 Unprocessable Entity*

```
{
    "message": "The given data was invalid."
}
```



### DELETE /api/ticket/comment/{id}

Uncomment a ticket.

__Request:__ id: Comment id.

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized)

__Body:__

*200 OK*

```
{
    "text": String,
    "id_ticket": Integer,
    "author": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "ticket": Ticket
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```


### PUT /api/ownedTickets

Return all tickets you have created.

__Request:__

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized)

__Body:__

*200 OK*

```
[{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}]
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```



### PUT /api/assignedTickets

Return all tickets you are assigned.

__Request:__

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized)

__Body:__

*200 OK*

```
[{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}]
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```




### PUT /api/ticket/{id}

Return data from a ticket.

__Request:__ id: Ticket id.

__Headers:__

* Accept : application/json
* Content-Type : application/json
* Authorization : Bearer ...

__Response:__

__Status Code:__ 200 (OK), 401 (Unauthorized)

__Body:__

*200 OK*

```
{
    "title": String,
    "description": String,
    "priority": String,
    "state": String,
    "id_proprietaire": Integer,
    "updated_at": Date,
    "created_at": Date,
    "id": Integer,
    "proprietaire": User
}
```

*401 Unauthorized*

```
{
    "message": "Unauthenticated."
}
```