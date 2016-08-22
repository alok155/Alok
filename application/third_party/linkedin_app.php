<?php

#Include necessary class files
require_once('config.php');
require_once('LinkedIn.OAuth2.class');

/**
 * This is wrapper class file which will call LinkedIn API functions and return result to the controller.
 */
class LinkedIn
{
    /**
     * Function to get LinkedIn Authorize URL and access token
    */
    function fnLinkedInConnect()
    {
        # Object of class
        $ObjLinkedIn = new LinkedInOAuth2();
        $strApiKey = LINKEDIN_API_KEY;
        $strSecreteKey = LINKEDIN_API_SECRETE_KEY;

        //put here your redirect url
        $strRedirect_url = LINKEDIN_CALLBACK_URL;

        $strCode = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';

        if ($strCode == "") {

            try {
                # Get LinkedIn Authorize URL
                #If the user authorizes your application they will be redirected to the redirect_uri that you specified in your request .
                $strGetAuthUrl = $ObjLinkedIn->getAuthorizeUrl($strApiKey, $strRedirect_url);
            } catch (Exception $e) {

            }
            header("Location: ".$strGetAuthUrl);
            exit;
        }

        # Get LinkedIn Access Token
        /**
         * Access token is unique to a user and an API Key. You need access tokens in order to make API calls to LinkedIn on behalf of the user who authorized your application.
         * The value of parameter expires_in is the number of seconds from now that this access_token will expire in (5184000 seconds is 60 days).
         * You should have a mechanism in your code to refresh the tokens before they expire in order to continue using the same access tokens.
         */
        $arrAccess_token = $ObjLinkedIn->getAccessToken($strApiKey, $strSecreteKey, $strRedirect_url, $strCode);
        $strAccess_token = $arrAccess_token["access_token"];

        $_SESSION['_oauth_token'] = $strAccess_token;
    }

    /**
     * To Get List of LinkedIn Company Pages.
     */
    function fnGetLinkedCompanyPages()
    {
        $strAccess_token = $_SESSION['_oauth_token'];

        # Object of class
        $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

        # Get List of company pages
        try {
            $arrAdminCompany = $ObjLinkedin->getAdminCompanies();
        } catch (Exception $e) {

        }

        $arrAdminCompanyValue = $arrAdminCompany["values"];
        $intTotalCount = count($arrAdminCompany["_total"]);

        $arrLinkedInPages = array();
        $intCount = 0;
        if (is_array($arrAdminCompanyValue) && count($arrAdminCompanyValue) > 0) {
            foreach ($arrAdminCompanyValue as $arrAdminCompanyInfo) {
                $intFlag = 0;

                $arrLinkedInPages[$intCount]["id"] = (int) $arrAdminCompanyInfo["id"];
                $arrLinkedInPages[$intCount]["name"] = stripslashes($arrAdminCompanyInfo["name"]);
            }
        }
        return $arrLinkedInPages;
    }

    /**
     * To Get List of LinkedIn User Profiles.
     */
    function fnGetLinkedUserProfile()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intUserId = "USER ID";

        if ($intUserId > 0) {
            # Object of class
            $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

            # Get List of company pages
            try {
                $arrLinkedInProfile = $ObjLinkedin->getUserProfile($intUserId);
            } catch (Exception $e) {

            }
            return $arrLinkedInProfile;
        }
    }

    /**
     * To Get List of LinkedIn Company Information.
     */
    function fnGetLinkedCompanyInformation()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intCompanyId = "COMPANY ID";

        if ($intCompanyId > 0) {
            # Object of class
            $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

            # Get List of company pages
            try {
                $arrLinkedInCompanyInformation = $ObjLinkedin->getCompany($intCompanyId);
            } catch (Exception $e) {

            }
            return $arrLinkedInCompanyInformation;
        }
    }

    /**
     * To get LinkedIn company updates
     */
    function fnGetLinkedInCompanyUpdates()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intCompanyId = "COMPANY ID";

        if ($intCompanyId > 0) {

            $intStart = 0;
            $intCount = 10;
            $intPage = 1;
            if ($intPage) {
                $intStart = ($intPage - 1) * 10;
            }
            $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

            try {
                $arrCompanyUpdate = $ObjLinkedin->getCompanyUpdates($intCompanyId, $intStart, $intCount);
            } catch (Exception $e) {

            }
        }
    }

    /**
     * To get LinkedIn user profile updates
     */
    function fnGetLinkedInUserProfileUpdates()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intUserId = "USER ID";

        if ($intUserId > 0) {

            $intStart = 0;
            $intCount = 10;
            $intPage = 1;
            if ($intPage) {
                $intStart = ($intPage - 1) * 10;
            }
            $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

            try {
                $arrUserProfileUpdates = $ObjLinkedin->getUserStatuses($intUserId, true, $intStart, $intCount);
            } catch (Exception $e) {

            }
        }
    }

    /**
     * Function to Send status Message on LinkedIn company Pages
     */
    function fnPostMessage()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intCompanyPageId = "COMPANY ID";
        $strStatusMessage = "STATUS MESSAGE";

        $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

        try {
            $strErrorMessage = '';
            $arrResponse = $ObjLinkedin->postToCompany($intCompanyPageId, $strStatusMessage);
            // not post given error
            if ($arrResponse['updateKey'] == "") {
                $strErrorMessage = "SET ERROR MESSAGE";
            }

        } catch (Exception $e) {

        }
        return $strErrorMessage;
    }
}