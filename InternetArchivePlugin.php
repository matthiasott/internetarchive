<?php
namespace Craft;

class InternetArchivePlugin extends BasePlugin
{
    public function getName()
    {
         return Craft::t('Internet Archive');
    }

    public function getVersion()
    {
        return '0.2.0';
    }

    public function getDeveloper()
    {
        return 'Matthias Ott';
    }

    public function getDeveloperUrl()
    {
        return 'https://matthiasott.com';
    }

    public function getDocumentationUrl()
    {
    return 'https://github.com/matthiasott/internetarchive';
    }

    public function getDescription()
    {
        return 'Archive entries of Craft CMS sites in the Internet Archive.';
    }
    
    public function getSettingsUrl()
    {
        return 'internetArchive/settings';
    }

    function init() 
    {
        craft()->on('entries.saveEntry', function(Event $event) {
            craft()->internetArchive->onSaveEntry($event);
        });
    }

}