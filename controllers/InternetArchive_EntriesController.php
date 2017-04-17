<?php

namespace Craft;

/**
 * Internet Archive entries controller
 */
class InternetArchive_EntriesController extends BaseController
{
    /**
     * Starts saving all active URLs and then redirects to the URL that sent the request (e.g. settings)
     *
     * @return null
     */
    public function actionSaveAll()
    {

        if(craft()->internetArchive->saveAllUrls()){
            $redirect = craft()->request->getUrlReferrer();
            $this->redirect($redirect);
        } else {
            $redirect = craft()->request->getUrlReferrer();
            $this->redirect($redirect);
        }
        

    }
}