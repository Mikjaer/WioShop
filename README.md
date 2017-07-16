 === WioSoft WordPress API Implementation Sample ===

This plugin is inteded for you to build upon for your own integrations, for high
volume websites it is recomended to built-in caching of the API Calls.

This software needs a professional high quality webhosting provider to run 
and will most likeley not work on discount-offerings, minimal requirements:

  - SSH Access
  - Curl
  - Json
  - Outgoing traffic allowed

1: Install zip into /wp-content/plugins/WioShop 
2: Alter the config.php file and add the endpoint url for your system
3: ... and a valid API Key for your system.
4: And activate plugin through WordPress 
5: Create a blank page and insert the shortcode [shop]
6: Menu-items to categories can be created like so: /?shop=categories&parent=100
   - Asuming that the default-page on the site contains the [shop]-shortcode

