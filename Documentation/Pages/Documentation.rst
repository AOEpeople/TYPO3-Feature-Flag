.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Allgemeine Informationen
================

Im Typo3 System können sogenannte *Feature Flags* definiert werden.
Diese können umgebungsabhängig (WIRK, TUA) oder manuell aktiviert oder deaktiviert werden, um damit verknüpfte
Funktionalität an- oder auszuschalten.
Der Redakteur kann beliebige Elemente im TYPO3 mittels des Feature Flag ein und ausblenden.
Der Entwickler kann in der Anwendung den Zustand des Feature Flag abfragen und unterschiedliche Verhalten
implementieren.

Anwendungsbeispiel: Frauddetection
----------------------------------

Diese versucht anhand der Browser- und Hardware Konfiguration des Käufers eine vorgelagerte Frauddetection durchzuführen.
Hierfür müssen wir, aufgrund von Datenschutzbestimmungen, eine zusätzliche "Permission" von den Käufern abfragen.
Sollte sich abzeichen, dass die Verkaufszahlen hierdurch beinflusst werden, möchte congstar die Möglichkeit haben
dieses Feature, inklusive Permission-Checkbox, ohne Hotfix, zu deaktiveren.

Verwendung
==========

Die Verwendung der Feature Flags aus Backend User-, Entwickler- und Admin Usersicht wird im Folgenden gezeigt.

Inhalte mit Feature Flag steuern
--------------------------------

**Datensatz ausblenden**
    In der "Bearbeiten"-Maske eines Datensatzes (z.B. Seite oder Inhaltselement) kann im Reiter "Feature Flag"
    konfiguriert werden, ob der Datensatz für ein aktives Feature Flag angezeigt oder ausgeblendet werden soll.
    Wird das Feature Flag unter "Hide this content element on Feature Flag" ausgewählt, so wird der Datensatz bei
    aktivem Feature Flag ausgeblendet.

**Datensatz einblenden**
    Wird das Feature Flag unter "Show this content element on Feature Flag" ausgewählt, so wird der Datensatz bei
    aktivem Feature Flag angezeigt.

.. image:: /Images/Documentation/resized_flag.png

Durch grüne (sichtbar) bzw. rote (versteckt) Sterne wird im Seitenbaum der Status eines Datensatzes mit hinterlegtem Feature Flag visualisiert.

.. image:: /Images/Documentation/feature_flag_stars.png

Entwickler
----------

Folgende Informationen sind für Entwickler relevant.

Anwendung mit Feature Flag steuern
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Für die Funktionalität ist eine eigene TYPO3 Extension zuständig (feature_flag - im TYPO3-Forge zu finden).
Um im EFT Quellcode auf Flags zugreifen zu können, wurde diese Extension in der Klasse tx_eft_system_featureFlag
gekapselt.
Diese Klasse kann per Dependency Injection geladen werden.

Feature Flag Abfrage::

    if ($this->featureFlag->isFeatureEnabled('frauddetection')){
        $this->addCheckboxForFraudDetection( $contextObj );
    }

Features umgebunsspezifisch steuern
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Im Xataface gibt es extra für das FeatureFlag einen neuen SettingType: "updateByFieldAndSetValue".

.. image:: /Images/Documentation/xataface.png

eID Zugriff
~~~~~~~~~~~

Der Zugriff über ein eID Script ist standardmäßig deaktiviert und kann über die Extension Konfiguration im BE oder
über XATAFACE aktiviert werden.

**Dies sollte niemals für Live-Systeme gemacht werden!**

Folgende Aktionen sind möglich über ein eID Script:

- **activate**
    Ermöglicht das Aktivieren von Features.
    Beispiel: ``{BASEURL}/index.php?eID=featureflag&action=activate&feature=test_feature``
- **deactivate**
    Ermöglicht das Deaktivieren von Features.
    Beispiel: ``{BASEURL}/index.php?eID=featureflag&action=deactivate&feature=test_feature``
- **flagentries**
    Setzt die mit dem Feature Flag referenzierten Content-Elemente auf visible oder invisible.
    Beispiel: ``{BASEURL}/index.php?eID=featureflag&action=flagentries``
- **status**
    Gibt zurück, ob ein Feature Flag aktiviert oder deaktiviert ist.
    Beispiel: ``{BASEURL}/index.php?eID=featureflag&action=status&feature=test_feature``

Eine beispielhafte Aktivierung eines Features würde dann folgende Aufrufe benötigen:

``activate`` => ``flagentries``

**Anschließend muss allerdings der Crawler noch den Seitenbaum neu durchlaufen.**

Die Response des eID-Scripts ist immer ein JSON Objekt, dass im Normalfall als status 200 zurückgibt.
Bei einer Statusabfrage eines Features wird als response der Aktivierungsstatus angegeben.

CLI Zugriff
~~~~~~~~~~~

Damit ein der Zugriff über CLI funktioniert, muss ein entsprechender User **_cli_feature_flag** existieren.

Folgende Aktionen sind möglich über den cli_dispatcher:

- **activate**
    Ermöglicht das Aktivieren von Features.
    ``./typo3/cli_dispatch.phpsh feature_flag activate test_feature``
- **deactivate**
    Ermöglicht das Deaktivieren von Features.
    ``./typo3/cli_dispatch.phpsh feature_flag deactivate test_feature``
- **flushcaches**
    Löscht alle FE Caches.
    Diese Funktionalität ist ausschließlich in der CLI Variante verfügbar, da sie einen eingeloggten BE User benötigt.
    ``./typo3/cli_dispatch.phpsh feature_flag flushCaches``
- **flagentries**
    Setzt die mit dem Feature Flag referenzierten Content-Elemente auf visible oder invisible.
    ``./typo3/cli_dispatch.phpsh feature_flag flagEntries``

Admin User
----------

Folgende Informationen sind für Admin User relevant.

Allen verfügbaren Features auflisten
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Die Flags befinden sich auf oberster Ebene im Seitebaum.

.. image:: /Images/Documentation/flags.jpg

Features aktivieren oder deaktivieren
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1. Datensatz bearbeiten
2. Checkbox "Aktiv" aktivieren oder deaktiviern
3. Sheduler Task feature_flag ausführen
4. Seiten Cache löschen

Feature Flags mit den Google Tag Manager verknüpfen
---------------------------------------------------

Hierfür bedienen wir uns dem GTM-TYPO3-Plugin.

.. image:: /Images/Documentation/gtm-feature.png

Wir legen auf den relevanten Seiten das GTM-Plugin an und lassen dieses anhand des FeatureFlags ein oder ausblenden.
Auf den DataLayer-Wert können wir dann wie gewohnt im GTM zugreifen (Defaultwert von DataLayer-Variablen beachten!)