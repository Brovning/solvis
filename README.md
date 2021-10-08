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

StartRegister | FunctionCode | Name | Type | Units | Description
------------- | ------------ | ---- | ---- | ----- | -----------
2049 | R | Zirkulation Betriebsart | int16 | Zirkulation | 1 - Aus, 2 - Puls, 3 - Temp, 4 - Warten
3840 | R | Analog Out 1 | int16 | Betriebsart | 0 - Auto PWM, 1 - Hand PWM, 2 - Auto analog, 3 - Hand analog
3845 | R | Analog Out 2 | int16 | Betriebsart | 0 - Auto PWM, 1 - Hand PWM, 2 - Auto analog, 3 - Hand analog
3850 | R | Analog Out 3 | int16 | Betriebsart | 0 - Auto PWM, 1 - Hand PWM, 2 - Auto analog, 3 - Hand analog
3855 | R | Analog Out 4 | int16 | Betriebsart | 0 - Auto PWM, 1 - Hand PWM, 2 - Auto analog, 3 - Hand analog
3860 | R | Analog Out 5 | int16 | Betriebsart | 0 - Auto PWM, 1 - Hand PWM, 2 - Auto analog, 3 - Hand analog
3865 | R | Analog Out 6 | int16 | Betriebsart | 0 - Auto PWM, 1 - Hand PWM, 2 - Auto analog, 3 - Hand analog
32768 | R | Unix Timestamp high | int16 |  | 
32769 | R | Unix Timestamp low | int16 |  | 
32770 | R | Version SC2 | int16 |  | 
32771 | R | Version NBG | int16 |  | 
33030 | R | S07 Solardruck | uint16 |  | 
33024 | R | S01 Speicher oben | int16 | °C | 
33025 | R | S02 Warmwasser | int16 | °C | 
33026 | R | S03 Speicherreferenz | int16 | °C | 
33027 | R | S04 Heizungspuffer oben | int16 | °C | 
33028 | R | S05 Solarvorlauf | int16 | °C | 
33029 | R | S06 Solarrücklauf | int16 | °C | 
33031 | R | S08 Solarkollektor | int16 | °C | 
33032 | R | S09 Heizungspuffer unten | int16 | °C | 
33033 | R | S10 Aussentemperatur | int16 | °C | 
33034 | R | S11 Zirkulation | int16 | °C | 
33035 | R | S12 Vorlauf Heizkreis 1 | int16 | °C | 
33036 | R | S13 Vorlauf Heizkreis 2 | int16 | °C | 
33037 | R | S14 Vorlauf Heizkreis 3 | int16 | °C | 
33038 | R | S15 Kaltwasser | int16 | °C | 
33039 | R | S16 unbenannt | int16 | °C | 
33040 | R | S17 Volumenstrom WW | int16 | l/min | 
33041 | R | S18 Volumenstrom Solar | int16 | l/min | 
33045 | R | DigIn Störungen |  |  | 
33042 | R | Analog In 1 | int16 | V | 
33043 | R | Analog In 2 | int16 | V | 
33044 | R | Analog In 3 | int16 | V | 
33280 | R | A01 Pumpe Zirkulation | uint8 | % | 
33281 | R | A02 Pumpe Solar | uint8 | % | 
33282 | R | A03 Pumpe Heizkreis 1 | uint8 | % | 
33283 | R | A04 Pumpe Heizkreis 2 | uint8 | % | 
33284 | R | A05 Pumpe Heizkreis 3 | uint8 | % | 
33285 | R | A06 | uint8 | % | 
33286 | R | A07 | uint8 | % | 
33287 | R | A08 Mischer HK1 auf | uint8 | % | 
33288 | R | A09 Mischer HK1 zu | uint8 | % | 
33289 | R | A10 Mischer HK2 auf | uint8 | % | 
33290 | R | A11 Mischer HK2 zu | uint8 | % | 
33291 | R | A12 Brenner | uint8 | % | 
33292 | R | A13 Brenner Stufe 2 | uint8 | % | 
33293 | R | A14 | uint8 | % | 
33294 | R | Analog Out O1 | int16 | V | 
33295 | R | Analog Out O2 | int16 | V | 
33296 | R | Analog Out O3 | int16 | V | 
33297 | R | Analog Out O4 | int16 | V | 
33298 | R | Analog Out O5 | int16 | V | 
33299 | R | Analog Out O6 | int16 | V | 
33536 | R | Laufzeit Brennerstufe 1 | int16 | h | 
33537 | R | Brennerstarts Stufe 1 | int16 |  | 
33538 | R | Laufzeit Brennerstufe 2 | int16 | h | 
33539 | R | Wärmeerzeuger SX aktuelle Leistung | int16 | W | 
33540 | R | Ionisationsstrom mA | int16 | mA | 
33792 | R | Meldungen Anzahl | int16 |  | 
33793 | R | Meldung 01 Code | int16 | StatsHeizkreis | 
33794 | R | Meldung 01 UnixZeit H | int16 |  | 
33795 | R | Meldung 01 UnixZeit L | int16 |  | 
33796 | R | Meldung 01 Par 1 | int16 |  | 
33797 | R | Meldung 01 Par 2 | int16 |  | 
33798 | R | Meldung 02 Code | int16 | StatsHeizkreis | 
33799 | R | Meldung 02 UnixZeit H | int16 |  | 
33800 | R | Meldung 02 UnixZeit L | int16 |  | 
33801 | R | Meldung 02 Par 1 | int16 |  | 
33802 | R | Meldung 02 Par 2 | int16 |  | 
33803 | R | Meldung 03 Code | int16 | StatsHeizkreis | 
33804 | R | Meldung 03 UnixZeit H | int16 |  | 
33805 | R | Meldung 03 UnixZeit L | int16 |  | 
33806 | R | Meldung 03 Par 1 | int16 |  | 
33807 | R | Meldung 03 Par 2 | int16 |  | 
33808 | R | Meldung 04 Code | int16 | StatsHeizkreis | 
33809 | R | Meldung 04 UnixZeit H | int16 |  | 
33810 | R | Meldung 04 UnixZeit L | int16 |  | 
33811 | R | Meldung 04 Par 1 | int16 |  | 
33812 | R | Meldung 04 Par 2 | int16 |  | 
33813 | R | Meldung 05 Code | int16 | StatsHeizkreis | 
33814 | R | Meldung 05 UnixZeit H | int16 |  | 
33815 | R | Meldung 05 UnixZeit L | int16 |  | 
33816 | R | Meldung 05 Par 1 | int16 |  | 
33817 | R | Meldung 05 Par 2 | int16 |  | 
33818 | R | Meldung 06 Code | int16 | StatsHeizkreis | 
33819 | R | Meldung 06 UnixZeit H | int16 |  | 
33820 | R | Meldung 06 UnixZeit L | int16 |  | 
33821 | R | Meldung 06 Par 1 | int16 |  | 
33822 | R | Meldung 06 Par 2 | int16 |  | 
33823 | R | Meldung 07 Code | int16 | StatsHeizkreis | 
33824 | R | Meldung 07 UnixZeit H | int16 |  | 
33825 | R | Meldung 07 UnixZeit L | int16 |  | 
33826 | R | Meldung 07 Par 1 | int16 |  | 
33827 | R | Meldung 07 Par 2 | int16 |  | 
33828 | R | Meldung 08 Code | int16 | StatsHeizkreis | 
33829 | R | Meldung 08 UnixZeit H | int16 |  | 
33830 | R | Meldung 08 UnixZeit L | int16 |  | 
33831 | R | Meldung 08 Par 1 | int16 |  | 
33832 | R | Meldung 08 Par 2 | int16 |  | 
33833 | R | Meldung 09 Code | int16 | StatsHeizkreis | 
33834 | R | Meldung 09 UnixZeit H | int16 |  | 
33835 | R | Meldung 09 UnixZeit L | int16 |  | 
33836 | R | Meldung 09 Par 1 | int16 |  | 
33837 | R | Meldung 09 Par 2 | int16 |  | 
33838 | R | Meldung 10 Code | int16 | StatsHeizkreis | 
33839 | R | Meldung 10 UnixZeit H | int16 |  | 
33840 | R | Meldung 10 UnixZeit L | int16 |  | 
33841 | R | Meldung 10 Par 1 | int16 |  | 
33842 | R | Meldung 10 Par 2 | int16 |  | 



#### Statusvariablen
Variablenname | Type | Units | Description
---- | ---- | ----- | -----------
\- | - | - | -


#### Profile

Name   | Typ
------ | -------
Solvis.Betriebsart.Int | Integer
Solvis.Hours.Int | Integer
Solvis.MilliAmpere.Int | Integer
Solvis.StatsHeizkreis.Int | Integer
Solvis.Volumenstrom.Int | Integer
Solvis.Watt.Int | Integer
Solvis.Zirkulation.Int | Integer


### 6. WebFront

Aktuell kein WebFront umgesetzt.


### 7. PHP-Befehlsreferenz

#### Empfehlung
Sofern nur eine Instanz des Solvis-Moduls im Einsatz ist, sollte die $InstanzID wie folgt dynamisch ermittelt werden und nicht statisch gesetzt werden, da somit ein Löschen und Neuinstallieren der Solvis-Instanz keine Auswirkung auf andere Skripte hat:

`$InstanzID = IPS_GetInstanceListByModuleID("{6F809C50-0314-84A3-14AE-00E32121DCF3}")[0];`


#### Funktionen
Noch keine Funktionen verfügbar.


### 8. Versionshistorie

#### v0.1
- initiales Release
