# LTI 1.3 Advantage Demo Tool
This code consists an example tool that utilizes Packbackbooks LTI 1.3 PHP library.

# Running The Example Code

## Setup
The example is all written in PHP, and it also contains a docker compose file for easy setup if you have docker installed.

### Registration and Deployment
First thing you will need is to configure your registration and deployment in the example code's fake registrations database.

This can be found in the example tool's code at `db/configs/example.json`.
To configure your registration add a JSON object into a `db/configs/example.json` file in the following format.

```javascript
{
    "<issuer>" : { // This will usually look something like 'http://example.com'
        "client_id" : "<client_id>", // This is the id received in the 'aud' during a launch
        "auth_login_url" : "<auth_login_url>", // The platform's OIDC login endpoint
        "auth_token_url" : "<auth_token_url>", // The platform's service authorization endpoint
        "key_set_url" : "<key_set_url>", // The platform's JWKS endpoint
        "private_key_file" : "<path_to_private_key>", // Relative path to the tool's private key
        "deployment" : [
            "<deployment_id>" // The deployment_id passed by the platform during launch
        ]
    }
}
```

To register your tool inside a platform, the platform will need two URLs

```
OIDC Login URL: http://localhost:9001/login.php
LTI Launch URL: http://localhost:9001/game.php
```

These URLs may vary if you do not use docker-compose to run the tool and instead run it locally.

### Running in Docker
To run in docker you will need both `docker` and `docker-compose`

To get the examples up and running in docker simply run:
```
docker-compose up --build
```

You're now free to launch in and use the tool.
