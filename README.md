 === WioSoft WordPress API Implementation Sample ===

This plugin is inteded for you to build upon for your own integrations, for high
volume websites it is recomended to built-in caching of the API Calls.

This software needs a professional high quality webhosting provider to run 
and will most likeley not work on discount-offerings, minimal requirements:

  - SSH Access
  - Curl
  - Json
  - Yaml
  - Outgoing traffic allowed

1: Install zip into /wp-content/plugins/WioShop and activate plugin through WordPress 
2: Create a blank page and insert the shortcode [shop]
3: Menu-items to categories can be created like so: /?shop=categories&parent=100
   - Asuming that the default-page on the site contains the [shop]-shortcode
4: Config-file is config.yaml, should be mostly self-explanatory



