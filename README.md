#  FeedNews.me

### This project is currently transitioning to a Laravel based project. A migration files between this and that project will not be made, however, most of the data can be easily restored with a manual touch. Look for the FeedNews repository for the latest news.

![alt text](https://www.petervdam.nl/storage/app/media/FeedNewsPreview.png)
A powerful dashboard tool to keep track of your favorite news sites, daily tasks, reminders, and so much more. FeedNews.me, previously called "It's my Homepage" brings you a site that you can set as startpage of your browser, or use Electron to run it as an app. I personally use it all day, with my news sources like Neowin, iDownloadblog and many more next to my Youtube subscriptions, todo-list and weather updates all in one place.

# Use online version
In case you wish to use the functionality, or just have a look around but you don't want to go through all the installation hussle. Visit [Feednews.me](http://feednews.me) and create a account.

# Beta stage
Until our 2.0 release, some functionality may be added, changed or removed. At it's current state, it's stable enough to use but it might contain a bug or two. No showstoppers. If you want a more stable release, use our old Silex version from our 1.5 release.

# Functionality
- Read and manage RSS Feeds
    - Assign colors to different sources
    - Assign FontAwesome icons to each source (optional)
    - Search items
    - Pin items to read later
    - Add pages to view later
    - Share a page with friends
    - Youtube support (theme switches to dark mode when watching video)
    
- Screensaver functionality (image at the bottom of this readme)
    - Set [yourdomain]/screensaver/ as screensaver to any OS and display random images with your news
    - Option to combine images from services like Unsplash with your own image gallery services
    - Show current weather condition and temperature
    
- Todo list
    - Need to keep track of things? Simply use the searchbar to add items, or use the todo page to manage your list. Quick and easy
    
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
- Documentation


# Installation
Use the config.php.dist file in the app directory to create your config file. You need to set your database config and enter you openWeatherMap API key. This is a required step for now. To setup your database, use the db-structure.sql file located in the database folder.

After saving your config, make sure to run the following commands from your terminal. Currently all commands need to run on both dev and production env. This will probably change in the future.

- fill .env file with server settings
- composer install
- bin/console doctrine:schema:create
- bin/console fos:user:create

If you want to change styling, also run:
- yarn install
- yarn build


You need to set one cronjobs so the feed data can be imported. For DirectAdmin I've used:
*/5	*	*	*	*	/usr/local/bin/php /home/USERNAME/domains/YOUR_DOMAIN/public_html/bin/console app:feeds:update


# Mobile support
This tool with all it's beauty also runs on your mobile device. To get the best experience, use chrome for Android or Safari on iOS and save the page as icon to your homescreen. This way you get a fullscreen experience on your mobile device, and have quick access to it's functionality, like the todo that is usefull for shoppinglists for example.

# Idea's?
I wish to hear your thoughts and idea's for this project. I've been working on this project for a year now, and love to improve it a lot more. It's not ready for multiusers yet. But if you know how to work with git, composer, npm this tool can be used daily. It's been my browsers homepage for a year now, can't live without! I have so many ideas poping in every day and I hope I can make something that many people will enjoy! Any help is appreciated. So if you want to help, code away and submit a pull request! If it's good I'm sure to add it!



[![StackShare](https://img.shields.io/badge/tech-stack-0690fa.svg?style=flat)](https://stackshare.io/webstylecenter/homepage)
