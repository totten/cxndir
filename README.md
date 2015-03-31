The CXN directory service.

## Tutorial

#### Get the code

```
git clone https://github.com/totten/cxnapp
cd cxndir
composer install
```

#### Generate an identity for the application:

```
bin/cxndir init 'C=US, O=CiviCRM, cn=example.localhost'
```

The autogenerated identity has a self-signed certificate. For a production
release, you'll need a signature from CiviRootCA.

#### Setup a virtual host for the 'web' folder

e.g. in Debian/Ubuntu with civicrm-buildkit:

```
cd web
amp create --url http://example.localhost
apache2ctl restart
curl http://example.localhost/
## Note: This should output the application description.
```

#### Connect a test instance of CiviCRM

In your local CiviCRM installation, edit civicrm.settings.php
and set:

```
define('CIVICRM_CXN_CA', 'none');
define('CIVICRM_CXN_APPS_URL', 'http://example.localhost/cxn/apps');
```

(Note: The above configuration is vulnerable to man-in-the-middle attacks.
It's acceptable for local development but should not be used in production
sites.  Consequently, there is no API for reading or writing these
settings.)