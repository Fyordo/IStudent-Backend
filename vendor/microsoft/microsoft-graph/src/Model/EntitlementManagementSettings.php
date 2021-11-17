<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* EntitlementManagementSettings File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Microsoft\Graph\Model;

/**
* EntitlementManagementSettings class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class EntitlementManagementSettings extends Entity
{
    /**
    * Gets the durationUntilExternalUserDeletedAfterBlocked
    *
    * @return \DateInterval|null The durationUntilExternalUserDeletedAfterBlocked
    */
    public function getDurationUntilExternalUserDeletedAfterBlocked()
    {
        if (array_key_exists("durationUntilExternalUserDeletedAfterBlocked", $this->_propDict)) {
            if (is_a($this->_propDict["durationUntilExternalUserDeletedAfterBlocked"], "\DateInterval") || is_null($this->_propDict["durationUntilExternalUserDeletedAfterBlocked"])) {
                return $this->_propDict["durationUntilExternalUserDeletedAfterBlocked"];
            } else {
                $this->_propDict["durationUntilExternalUserDeletedAfterBlocked"] = new \DateInterval($this->_propDict["durationUntilExternalUserDeletedAfterBlocked"]);
                return $this->_propDict["durationUntilExternalUserDeletedAfterBlocked"];
            }
        }
        return null;
    }
    
    /**
    * Sets the durationUntilExternalUserDeletedAfterBlocked
    *
    * @param \DateInterval $val The durationUntilExternalUserDeletedAfterBlocked
    *
    * @return EntitlementManagementSettings
    */
    public function setDurationUntilExternalUserDeletedAfterBlocked($val)
    {
        $this->_propDict["durationUntilExternalUserDeletedAfterBlocked"] = $val;
        return $this;
    }
    
    /**
    * Gets the externalUserLifecycleAction
    * One of None, BlockSignIn, or BlockSignInAndDelete.
    *
    * @return AccessPackageExternalUserLifecycleAction|null The externalUserLifecycleAction
    */
    public function getExternalUserLifecycleAction()
    {
        if (array_key_exists("externalUserLifecycleAction", $this->_propDict)) {
            if (is_a($this->_propDict["externalUserLifecycleAction"], "\Microsoft\Graph\Model\AccessPackageExternalUserLifecycleAction") || is_null($this->_propDict["externalUserLifecycleAction"])) {
                return $this->_propDict["externalUserLifecycleAction"];
            } else {
                $this->_propDict["externalUserLifecycleAction"] = new AccessPackageExternalUserLifecycleAction($this->_propDict["externalUserLifecycleAction"]);
                return $this->_propDict["externalUserLifecycleAction"];
            }
        }
        return null;
    }
    
    /**
    * Sets the externalUserLifecycleAction
    * One of None, BlockSignIn, or BlockSignInAndDelete.
    *
    * @param AccessPackageExternalUserLifecycleAction $val The externalUserLifecycleAction
    *
    * @return EntitlementManagementSettings
    */
    public function setExternalUserLifecycleAction($val)
    {
        $this->_propDict["externalUserLifecycleAction"] = $val;
        return $this;
    }
    
}
