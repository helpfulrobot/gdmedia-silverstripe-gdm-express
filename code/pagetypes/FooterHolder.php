<?php

class FooterHolder extends RedirectorPage
{
    public static $defaults = array(
        "ShowInMenus" => 0,
        "ShowInSearch" => 0
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('RedirectorDescHeader');
        $fields->removeByName('RedirectionType');
        $fields->removeByName('LinkToID');
        $fields->removeByName('ExternalURL');

        return $fields;
    }

    /**
     * Return the link to the first child page.
     */
    public function redirectionLink()
    {
        $childPage = $this->Children()->first();

        if ($childPage) {
            // If we're linking to another redirectorpage then just return the URLSegment, to prevent a cycle of redirector
            // pages from causing an infinite loop.  Instead, they will cause a 30x redirection loop in the browser, but
            // this can be handled sufficiently gracefully by the browser.
            if ($childPage instanceof RedirectorPage) {
                return $childPage->regularLink();

            // For all other pages, just return the link of the page.
            } else {
                return $childPage->Link();
            }
        }
    }
    
    public function syncLinkTracking()
    {
        // If we don't have anything to link to, then we have a broken link.
        if (!$this->Children()) {
            $this->HasBrokenLink = true;
        }
    }
}

class FooterHolder_Controller extends RedirectorPage_Controller
{
}
