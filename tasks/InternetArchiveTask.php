<?php
namespace Craft;

class InternetArchiveTask extends BaseTask {
    protected function defineSettings() {
        return array(
            'queue' => AttributeType::Mixed,
            'rows'  => AttributeType::Number
        );
    }

    public function getDescription() {
        return Craft::t('Sending URLs to the Internet Archive');
    }

    public function getTotalSteps() {
        return $this->getSettings()->rows;
    }

    public function runStep($step) {
        $queue = $this->getSettings()->queue;
        $row = $queue[$step];

        craft()->internetArchive->archiveUrl($row['url']);

        return true;
    }
}