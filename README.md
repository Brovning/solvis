[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-5.0%20%3C-green.svg)](https://community.symcon.de/t/ip-symcon-5-0-verfuegbar/47528)
[![Version](https://img.shields.io/badge/Symcon%20Version-6.0%20%3E-green.svg)](https://community.symcon.de/t/ip-symcon-6-0-ist-ab-jetzt-verfuegbar/125851)
![Code](https://img.shields.io/badge/Code-PHP-blue.svg)


# Solvis
IP-Symcon (IPS) Modul für Solvis Heizungen mit Modbus TCP Unterstützung.


### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)
8. [Versionshistorie](#8-versionshistorie)

### 1. Funktionsumfang

Dieses Modul erstellt anhand der Konfiguration der Solvis Instanz den nötigen Client Socket und das dazugehörige Modbus Gateway. Sofern diese bereits vorhanden sind, werden keine weiteren Client Sockets oder Modbus Gateways erstellt.
Unterhalb der Solvis Instanz werden die Modbus Adressen erstellt.


### 2. Voraussetzungen

* IP-Symcon ab Version 5.0
* Die Solvis Heizung muss Modbus TCP unterstützen!
* Im Konfigurationsmenü der Solvis Heizung muss folgendes aktiviert werden:

Vorbereiten der SC-03 auf die Modbus-Schnittstelle Wechsel in den Installateur-Modus (Zugangscode über deinen Heizi oder SOLVIS) Unter „Sonstiges“ auf Punkt „Modbus“, hier die vorgegebene Adresse nutzen oder bei mehreren Anlagen entsprechend ändern. Der „Modus“ bleibt vorerst auf TCP(read) stehen.

![alt text](./docs/Solvis_Sonstiges_Modbus.jpg?raw=true "Solvis > Sonstiges > Modbus")

![alt text](./docs/Solvis_Sonstiges_Modbus-read.jpg?raw=true "Solvis > Sonstiges > Modbus read")


### 3. Software-Installation

#### Variante 1 (empfohlen): Module Store

Über den in der IP Symcon Console integrierten Module Store das 'Solvis'-Modul installieren:

![alt text](./docs/symcon_module-store.jpg?raw=true "Symcon > Module Store > 'Solvis'-Modul")

Anschließend steht das Modul zur Verfügung und eine Solvis Instanz kann hinzugefügt werden.


#### Variante 2: Module Control

Über das in der IP Symcon Console (unter Core Instances/Kerninstanzen) enthaltene Module Control die URL https://github.com/Brovning/solvis manuell hinzufügen.

![alt text](./docs/symcon_module-control.jpg?raw=true "Symcon Console > Module Control > URL hinzufuegen")

Anschließend steht das Modul zur Verfügung und eine Solvis Instanz kann hinzugefügt werden.


### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' ist das Solvis-Modul unter dem Hersteller 'Solvis' aufgeführt.

__Konfigurationsseite__:

Name     | Beschreibung
-------- | ------------------
Open | Schalter zum Aktivieren und Deaktivieren der Instanz. Default: aus
IP | IP-Adresse des Solvis-Stromspeichers im lokalen Netzwerk (IPv4)
Port | Port, welcher im Solvis unter dem Menüpunkt Modbus angegeben wurde. Default: 502
Geräte Id | Modbus Geräte ID, welche im Solvis Menü gesetzt werden kann. Default: 1
Abfrage-Intervall	| Intervall (in Sekunden) in welchem die Modbus-Adressen abgefragt werden sollen. Achtung: Die Berechnung der Wirkarbeit (Wh/kWh) wird exakter, je kleiner der Abfrage-Intervall gewählt wird. Jedoch je kleiner der Abfrage-Intervall, umso höher die Systemlast und auch die Archiv-Größe bei Logging! Default: 60 Sekunden


### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusregister
Das Solvis-Modul ermöglicht den einfachen und schnellen Zugriff auf die wichtigsten und am häufigsten benötigten Daten.

StartRegister | Size | FunctionCode | Name | Type | Units | Description
------------- | ---- | ------------ | ---- | ---- | ----- | -----------



#### Statusvariablen
Variablenname | Type | Units | Description
---- | ---- | ----- | -----------


#### Profile

Name   | Typ
------ | -------
Solvis.Betriebsart.Int | Integer
Solvis.Hours.Int | Integer
Solvis.MilliAmpere.Int | Integer
Solvis.StatsHeizkreis.Int | Integer
Solvis.Volumenstrom.Int | Integer
Solvis.Zirkulation.Int | Integer


### 6. WebFront

Aktuell kein WebFront umgesetzt.


### 7. PHP-Befehlsreferenz

#### Empfehlung
Sofern nur eine Instanz des Solvis-Moduls im Einsatz ist, sollte die $InstanzID wie folgt dynamisch ermittelt werden und nicht statisch gesetzt werden, da somit ein Löschen und Neuinstallieren der Solvis-Instanz keine Auswirkung auf andere Skripte hat:

`$InstanzID = IPS_GetInstanceListByModuleID("{6F809C50-0314-84A3-14AE-00E32121DCF3}")[0];`


#### Funktionen



### 8. Versionshistorie

#### v0.1
- initiales Release
