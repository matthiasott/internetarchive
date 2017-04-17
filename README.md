# Internet Archive Plugin for Craft CMS

Plugin for [Craft CMS](https://craftcms.com) that notifies the Internet Archive to archive entries on save.

## Installation

1. Download & unzip the file and place the `internetarchive/` directory into your `craft/plugins/` directory.
2.  -OR- do a `git clone https://github.com/matthiasott/internetarchive.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`.
3. In the Craft Control Panel go to Settings > Plugins and click the “Install” button next to “Internet Archive”.

## Features

The plugin pings the Internet Archive on every save of a published entry to archive the corresponding URL. It can then be accessed using the Internet Archive's [Wayback Machine](https://archive.org/web/). More information on how to trigger an archival and why this is a good idea can be found on the [IndieWeb Wiki](https://indieweb.org/Internet_Archive).

If you want to send all URLs of your site at once, you can do so on the plugin’s settings page.

## Changelog

### 0.2.0

* Save all URLs of your site at once at the click of a button.

### 0.1.0

* First version

## Roadmap

- Provide plugin settings to turn off saving URLs for certain entry types
- Archive all URLs of links within an entry, too
- …

## Thank you!
Huge thanks to everyone involved in the Internet Archive project for saving our knowledge on the web and also to the [IndieWeb community](https://indieweb.org/), especially [Tantek Çelik](http://tantek.com).

## License

Code released under [the MIT license](https://github.com/matthiasott/internetarchive/LICENSE).

## Author

Matthias Ott    
<mail@matthiasott.com>    
<https://matthiasott.com>    
<https://twitter.com/m_ott>