# Local Installation Guide

Navigate to the plugin directory and run the following commands

1. `git clone https://github.com/Gabrielb102/super-logistics.git`
2. `cd super-logistics`
3. `composer install` 
4. `composer dumpautoload -o`
5. `npm install`
6. `npm run start` <- starts continuous vue compilation
7. Activate plugin

To deploy on WP installation: 
1. `npm run package`
2. Take zip file located in new "build" directory and upload to WP
