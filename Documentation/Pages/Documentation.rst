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

.. image:: /Images/Documentation/resized_flag.png

Record sthat are connected to a feature flag have a star icon in the page tree. A green star means the element is
visible, while hidden elements have a red star.

.. image:: /Images/Documentation/feature_flag_stars.png

Developers
----------

The following information is relevant for developers.

Controlling the application with a feature flag
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Feature flag query example::

    if ($this->featureFlag->isFeatureEnabled('frauddetection')){
        $this->addCheckboxForFraudDetection( $contextObj );
    }

eID Access
~~~~~~~~~~

Access via a eID script is disabled by default and can be enabled using the extension configuration in the BE.

**This should never be done on live systems!**

Following actions are possible with an eID script:

- **activate**
    Activates a feature.
    Example: ``{BASEURL}/index.php?eID=featureflag&action=activate&feature=test_feature``
- **deactivate**
    Deactivates a feature.
    Example: ``{BASEURL}/index.php?eID=featureflag&action=deactivate&feature=test_feature``
- **flagentries**
    Updates the visibility of content elements connected to the feature flag.
    Example: ``{BASEURL}/index.php?eID=featureflag&action=flagentries``
- **status**
    Returns, whether a feature flag is activated or not.
    Example: ``{BASEURL}/index.php?eID=featureflag&action=status&feature=test_feature``

Activating a feature can be done in two calls:

``activate`` => ``flagentries``

**After that, the crawler needs to crawl the page tree again**

**Anschließend muss allerdings der Crawler noch den Seitenbaum neu durchlaufen.**

The eID script always returns a JSON object. This is usually returnes with a status 200.
When querying for a flags status, the response is the activation status.

CLI Access
~~~~~~~~~~

A user **_cli_feature_flag** must exist for CLI access to work.

Following actions are possible via the cli_dispatcher:

- **activate**
    Activates a feature.
    ``./typo3/cli_dispatch.phpsh feature_flag activate test_feature``
- **deactivate**
    Deactivates a feature.
    ``./typo3/cli_dispatch.phpsh feature_flag deactivate test_feature``
- **flushcaches**
    Flushes all FE caches.
    This needs a logged in BE user.
    ``./typo3/cli_dispatch.phpsh feature_flag flushCaches``
- **flagentries**
    Updates the visibility of content elements connected to the feature flag.
    ``./typo3/cli_dispatch.phpsh feature_flag flagEntries``

Admin User
----------

Following information is relevant for admin users.

List all available features
~~~~~~~~~~~~~~~~~~~~~~~~~~~

The feature flags can be found on the page tree root.

.. image:: /Images/Documentation/flags.jpg

Activate or deactivate features
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1. Edit record
2. Check or uncheck the 'active' checkbox
3. Run sheduler task 'feature_flag'
4. Clear page cache

Connect feature flags with Google Tag Manager
---------------------------------------------

For this, the GTM-TYPO3-Plugin is used.

.. image:: /Images/Documentation/gtm-feature.png

On relevant pages, the GTM plugin can be added and shown or hidden using the feature flag. The DataLayer value can be
accessed as usual in GTM. (Pay attention to the default value of DataLayer variables!)
