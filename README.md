#  NewsFeed.me
A powerfull dashboard tool to keep track of your favorite news sites, daily tasks, reminders, and so much more. NewsFeed.me, previously calles "It's my Homepage" brings you a site that you can set as startpage of your browser, or use Electron to run it as an app. 

#Functionality
- Read and manage RSS Feeds
    - Assign colors to different sites
    - Search items
    - Pin items to read later
    - Add pages to view later
    - Share a page with friends
    
- Screensaver functionality
    - Set [yourdomain]/screensaver/ as screensaver to any OS and display random images with your news
    - Option to combine images from services like Unsplash with your own image gallery services
    - Show current weather condition and temperature
    
- Checklist
    - Need to keep track of things? Simply use the searchbar to add items, or use the checklist page to manage your list. Quick and easy
    
- Weather radar
    - It currently displays just weather of the Netherlands, but  will add support for more countries later
    - Show the current weather conditions of your location (set up in config)
    
- Image share functionality
    - Share an image with a shortlink from mobile or from clipboard (soon available)
    - View and remove shared images
    - Share previous shared images
    
- Notes
    - Add one or more notes with autosaving. It's saved while you type.
    
    
# Wishlist
- Plugins
    - Some options might not be interesting for the public. For that a plugin way of adding icons without git's interference would be a nice addition, and adds flexibility for those who like to use this tool.
    - Plugin share aka Play Store?
- Themes
    - Design always has a different taste with each and everyone. So adding theme support would be nice.
- Users
    - Option to make the service open to public so visitors can sign up and use the too. Not only one person. (high prio)
    - Better way to sync feeds in case many people sign up. (prevention of double feeds, double data php timeouts etc)
- Layout improvements
- Documentation

# Installation
Use the config.php.dist file in the app directory to create your config file. You need to set your database config and enter you openWeatherMap API key. This is a required step for now.

After saving your config, make sure to run the following commands from your terminal. Currently all commands need to run on both dev and production env. This will probably change in the future.

- npm install
- composer install
- gulp

In case the gulp command gives an error, "npm install --global gulp" will install gulp globally.

You need to set two cronjobs so the feeds and weather data are imported. For DirectAdmin I've used:
*/5	*	*	*	*	/usr/bin/wget -O /dev/null -q http://[yourdomain]/feed/update/
*/15	*	*	*	*	/usr/bin/wget -O /dev/null -q http://[yourdomain]/weather/update


#### Ignore X-Frame headers
For Google Chrome users, it's "recommended" to install Ignore X-Frame headers extension. Some site's ask the browser to block the site if it's shown within an iframe. Because all the pages you open are within an iframe this might cause issues. I've been using this extension for over a year without a security problem. Just make sure you don't do any payments within an iframe from untrusted sources if you enable this.

# Mobile support
This tool with all it's beauty also runs on your mobile device. To get the best experience, use chrome for Android or Safari on iOS and save the page as icon to your homescreen. This way you get a fullscreen experience on your mobile device, and have quick access to it's functionality, like the checklist that is usefull for shoppinglists for example.

Please not that some sites, that have the previous mentioned X-Frame headers might not open on your mobile device. Luckely, a button is placed to open the page in a browser view. On anroid, a simply hit on the back button brings you back to the app. Most site's will work!

It's recommended to load the page on WIFI if your low on your data limit the first time you open it on your mobile after running gulp. If you do so, all css and javascripts will be cached until you run gulp again. It's not megabytes of a difference, but in case your developing this tool it's a usefull reminder.

#Idea's?
I wish to hear your thoughts and idea's for this project. I've been working on this project for a year now, and love to improve it a lot more. It's not ready for multiusers yet. But if you know how to work with git, composer, npm this tool can be used daily. It's been my browsers homepage for a year now, can't live without! I have so many ideas poping in every day and I hope I can make something that many people will enjoy! Any help is appreciated. So if you want to help, code away and submit a pull request! If it's good I'm sure to add it!


[![StackShare](https://img.shields.io/badge/tech-stack-0690fa.svg?style=flat)](https://stackshare.io/webstylecenter/homepage)
