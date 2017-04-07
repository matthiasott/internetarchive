<?php
namespace Craft;

class InternetArchiveService extends BaseApplicationComponent
{
    /**
     * Notify the Internet Archive to archive an entry on save
     *
     * @param Event $event Craft's onSaveEntry event
     *
     */
    public function onSaveEntry($event) {

        $entry = $event->params['entry'];
        $url = $entry->url;

        if ($entry->getStatus() == 'live') {

            if (preg_match("/(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:\/[^\"\'\s]*)?/uix", $url)) {

                $options = 
                array(CURLOPT_URL => ('https://web.archive.org/save/' . $entry->url),
                      CURLOPT_HEADER => true,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_HTTPHEADER => array('Accept: application/json'),
                      CURLOPT_USERAGENT => "Craft CMS");
                $ch = curl_init();
                curl_setopt_array($ch, $options);
                $response = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);

                if ($info['http_code'] > 400) {
                    // Error message
                    craft()->userSession->setError(Craft::t("Error " . $info['http_code'] . ": Could not notify the Internet Archive."));
                } else 
                if ($info['http_code'] >= 200) {
                    // Success message
                    craft()->userSession->setNotice(Craft::t('Internet Archive notified successfully.'));
                }
            }
        }
    }
}