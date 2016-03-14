RestApiExample
==============

A Symfony project created on March 12, 2016, 8:07 am.

Create a client
==============

php app/console oauth-server:client:create --redirect-uri="http://127.0.0.1:8000/" --grant-type="authorization_code" --grant-type="password" --grant-type="refresh_token" --grant-type="token" --grant-type="client_credentials"

Get client credentials
==============

http://127.0.0.1:8000/oauth/v2/token?client_id=1_5etsr4gxgpkwo408ccckwskg8scs408c44skc0so08oooccc8&client_secret=4wmcb7iocb6s8g4kos8g4w4c80w840s8s8s4ccw00kow8k044c&grant_type=client_credentials


