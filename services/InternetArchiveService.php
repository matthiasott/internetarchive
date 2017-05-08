<?php
namespace Craft;

class InternetArchiveService extends BaseApplicationComponent
{   
    /**
     * Array of URLs.
     *
     * @var array
     */
    protected $urls = array();

    private $queue = array();

    /**
     * Get the url for a saved entry and notify the Internet Archive
     *
     * @param Event $event Craft's onSaveEntry event
     *
     */
    public function onSaveEntry($event) 
    {

        $entry = $event->params['entry'];
        $url = $entry->url;

        if ($entry->getStatus() == 'live') {

          if (preg_match("/(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:\/[^\"\'\s]*)?/uix", $url)) {

            $http_code = $this->archiveUrl($url);

            if ($http_code > 400) {
                // Error message
                craft()->userSession->setError(Craft::t("Error " . $info['http_code'] . ": Could not notify the Internet Archive."));
            } else 
            if ($http_code >= 200) {
                // Success message
                craft()->userSession->setNotice(Craft::t('Internet Archive notified successfully.'));
            }

          }
        }
    }

    /**
     * Sends a URL to the Internet Archive via cURL.
     *
     * @return int The returned HTTP status code
     */
    public function archiveUrl($url) {

        $options = 
          array(CURLOPT_URL => ('https://web.archive.org/save/' . $url),
                CURLOPT_HEADER => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array('Accept: application/json'),
                CURLOPT_USERAGENT => "Craft CMS");
          $ch = curl_init();
          curl_setopt_array($ch, $options);
          $response = curl_exec($ch);
          $info = curl_getinfo($ch);
          curl_close($ch);

          return $info['http_code'];

    }

    /**
     * Adds URL to queue array
     *
     */
    public function addToQueue($url) {
        array_push($this->queue, array(
            'url' => $url
        ));
    }

    /**
     * Returns all sections that have URLs.
     *
     * @return array An array of Section instances
     */
    public function getSectionsWithUrls()
    {
        return array_filter(craft()->sections->allSections, function ($section) {
            return $section->isHomepage() || $section->urlFormat;
        });
    }

    /**
     * Gets all active URLs, fills queue, and creates tasks to save the URLs in the Internet Archive
     *
     */
    public function saveAllUrls()
    {

        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->limit = null;

        foreach ($criteria as $entry) {

          if($entry->url) {
            $this->addToQueue($entry->url);
          }

        }

        $rows = count($this->queue);

        if ($rows > 0) {
            craft()->tasks->createTask('InternetArchive', 'Notifying the Internet Archive…', array(
                'queue' => $this->queue,
                'rows'  => $rows
            ));
        }

        return true;

    }
}