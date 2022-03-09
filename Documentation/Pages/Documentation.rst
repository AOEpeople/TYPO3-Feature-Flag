.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


General Information
===================

So called *feature flags* can be defined in the TYPO3 system.
They can be activated or deactivated either manually or dependant on the environment to enable or disable a feature
connected to it.
An editor can connect any content element with a feature flag to show or hide it when the flag is enabled or disabled.
A developer can query the state of a flag in the application and implement different behavior for each state.

Usage
=====

This chapter explains the usage of feature flags from a backend users, developers and admin users perspective.

Controlling content with feature flags
--------------------------------------

In the 'edit' view of a record (e.g. a page or content element) in the 'Feature Flag' tab it is possible to
configure an element to either show or hide, when a feature flag is active.

**Hide a record**
    If the feature flag is selected under 'Hide this content element on Feature Flag', the record will be hidden
    when the feature flag is active.

**Show a record**
    If the feature flag is selected under 'Show this content element on Feature Flag', the record will be shown
    when the feature flag is active.

Record sthat are connected to a feature flag have a star icon in the page tree. A green star means the element is
visible, while hidden elements have a red star.

Developers
----------

The following information is relevant for developers.

Controlling the application with a feature flag
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Feature flag query example::

    if ($this->featureFlag->isFeatureEnabled('frauddetection')){
        $this->addCheckboxForFraudDetection( $contextObj );
    }

Console Commands (Cli)
~~~~~~~~~~

Following actions are possible via typo3 console:

- **activate**
    Activates a feature.
    ``vendor/bin/typo3 featureflag:activate test_feature``
- **deactivate**
    Deactivates a feature.
    ``vendor/bin/typo3 featureflag:deactivate test_feature``
- **flagentries**
    Updates the visibility of content elements connected to the feature flag.
    ``vendor/bin/typo3 featureflag:toggleRecords``

Admin User
----------

Following information is relevant for admin users.

List all available features
~~~~~~~~~~~~~~~~~~~~~~~~~~~

The feature flags can be found on the page tree root.

Activate or deactivate features
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1. Edit record
2. Check or uncheck the 'active' checkbox
3. Run scheduler task 'feature_flag'
4. Clear page cache

Connect feature flags with Google Tag Manager
---------------------------------------------

For this, the GTM-TYPO3-Plugin is used.

On relevant pages, the GTM plugin can be added and shown or hidden using the feature flag. The DataLayer value can be
accessed as usual in GTM. (Pay attention to the default value of DataLayer variables!)
