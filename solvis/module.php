<?php

require_once __DIR__ . '/../libs/myFunctions.php';  // globale Funktionen

define("DEVELOPMENT", true);

// Modul Prefix
if (!defined('MODUL_PREFIX'))
{
	define("MODUL_PREFIX", "Solvis");
}

// ArrayOffsets
if (!defined('IMR_START_REGISTER'))
{
	define("IMR_START_REGISTER", 0);
//	define("IMR_END_REGISTER", 3);
//	define("IMR_SIZE", 1);
//	define("IMR_RW", 1);
	define("IMR_FUNCTION_CODE", 1);
	define("IMR_NAME", 2);
	define("IMR_DESCRIPTION", 3);
	define("IMR_TYPE", 4);
	define("IMR_UNITS", 5);
}

	class Solvis extends IPSModule
	{
		use myFunctions;

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			// *** Properties ***
			$this->RegisterPropertyBoolean('active', 'true');
			$this->RegisterPropertyString('hostIp', '');
			$this->RegisterPropertyInteger('hostPort', '502');
			$this->RegisterPropertyInteger('hostmodbusDevice', '1');
			$this->RegisterPropertyBoolean('readIC120', 'false');
            $this->RegisterPropertyBoolean('readIC121', 'false');
            $this->RegisterPropertyBoolean('readIC122', 'false');
            $this->RegisterPropertyBoolean('readIC123', 'false');
            $this->RegisterPropertyBoolean('readIC124', 'false');
			$this->RegisterPropertyBoolean('readI160', 'false');
			$this->RegisterPropertyBoolean('readOnePhaseInverter', 'false');
			$this->RegisterPropertyInteger('pollCycle', '60');

			// *** Erstelle Variablen-Profile ***
			$this->checkProfiles();
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

			//Properties
			$active = $this->ReadPropertyBoolean('active');
			$hostIp = $this->ReadPropertyString('hostIp');
			$hostPort = $this->ReadPropertyInteger('hostPort');
			$hostmodbusDevice = $this->ReadPropertyInteger('hostmodbusDevice');
			$hostSwapWords = 0; // Solvis = false
			$pollCycle = $this->ReadPropertyInteger('pollCycle') * 1000;

			$archiveId = $this->getArchiveId();
			if (false === $archiveId)
			{
				// no archive found
				$this->SetStatus(201);
			}

			// Workaround für "InstanceInterface not available" Fehlermeldung beim Server-Start...
			if (KR_READY != IPS_GetKernelRunlevel())
			{
				// --> do nothing
			}
			else if("" == $hostIp)
			{
				// keine IP --> inaktiv
				$this->SetStatus(104);
			}
			// Instanzen nur mit Konfigurierter IP erstellen
			else
			{
				$this->checkProfiles();
				list($gatewayId_Old, $interfaceId_Old) = $this->readOldModbusGateway();
				list($gatewayId, $interfaceId) = $this->checkModbusGateway($hostIp, $hostPort, $hostmodbusDevice, $hostSwapWords);

				$parentId = $this->InstanceID;

				/* ****** Fronius Register **************************************************************************
					HINWEIS! Diese Register gelten nur für Wechselrichter. Für Fronius String Controls und Energiezähler sind diese Register nicht relevant
					************************************************************************************************** */
				$modelRegister_array = array(
					array(2049,"R","Zirkulation Betriebsart", "1 - Aus 2 - Puls 3 - Temp 4 - Warten","int16", ""),
					array(3840,"R","Analog Out 1", "Status,0 - Auto PWM 1 - Hand PWM 2 - Auto analog 3 - Hand analog","int16", ""),
					array(3845,"R","Analog Out 2", "Status,0 - Auto PWM 1 - Hand PWM 2 - Auto analog 3 - Hand analog","int16", ""),
					array(3850,"R","Analog Out 3", "Status,0 - Auto PWM 1 - Hand PWM 2 - Auto analog 3 - Hand analog","int16", ""),
					array(3855,"R","Analog Out 4", "Status,0 - Auto PWM 1 - Hand PWM 2 - Auto analog 3 - Hand analog","int16", ""),
					array(3860,"R","Analog Out 5", "Status,0 - Auto PWM 1 - Hand PWM 2 - Auto analog 3 - Hand analog","int16", ""),
					array(3865,"R","Analog Out 6", "Status,0 - Auto PWM 1 - Hand PWM 2 - Auto analog 3 - Hand analog","int16", ""),
//					array(32768,"R","Unix Timestamp high -- --","","??",),
//					array(32769,"R","Unix Timestamp low","","??",),
//					array(32770,"R","Version SC2","","??",),
//					array(32771,"R","Version NBG","","??",),
					array(33024,"R","Temp S1","","int16", "°C"),
					array(33025,"R","Temp S2","","int16", "°C"),
					array(33026,"R","Temp S3","","int16", "°C"),
					array(33027,"R","Temp S4","","int16", "°C"),
					array(33028,"R","Temp S5","","int16", "°C"),
					array(33029,"R","Temp S6","","int16", "°C"),
					array(33030,"R","S7","","int16", "°C"),
					array(33031,"R","Temp S8","","int16", "°C"),
					array(33032,"R","Temp S9","","int16", "°C"),
					array(33033,"R","Temp S10","","int16", "°C"),
					array(33034,"R","Temp S11","","int16", "°C"),
					array(33035,"R","Temp S12","","int16", "°C"),
					array(33036,"R","Temp S13","","int16", "°C"),
					array(33037,"R","Temp S14","","int16", "°C"),
					array(33038,"R","Temp S15","","int16", "°C"),
					array(33039,"R","Temp S16","","int16", "°C"),
					array(33040,"R","Volumenstrom S17","","int16", "l/min"),
					array(33041,"R","Volumenstrom S18","","int16", "l/min"),
					array(33042,"R","Analog In 1","","int16", "V"),
					array(33043,"R","Analog In 2","","int16", "V"),
					array(33044,"R","Analog In 3","","int16", "V"),
					array(33045,"R","DigIn Störungen ***","","",""),
					array(33280,"R","Ausgang A1","","int16", "%"),
					array(33281,"R","Ausgang A2","","int16", "%"),
					array(33282,"R","Ausgang A3","","int16", "%"),
					array(33283,"R","Ausgang A4","","int16", "%"),
					array(33284,"R","Ausgang A5","","int16", "%"),
					array(33285,"R","Ausgang A6","","int16", "%"),
					array(33286,"R","Ausgang A7","","int16", "%"),
					array(33287,"R","Ausgang A8","","int16", "%"),
					array(33288,"R","Ausgang A9","","int16", "%"),
					array(33289,"R","Ausgang A10","","int16", "%"),
					array(33290,"R","Ausgang A11","","int16", "%"),
					array(33291,"R","Ausgang A12","","int16", "%"),
					array(33292,"R","Ausgang A13","","int16", "%"),
					array(33293,"R","Ausgang A14","","int16", "%"),
					array(33294,"R","Analog Out O1","","int16", "V"),
					array(33295,"R","Analog Out O2","","int16", "V"),
					array(33296,"R","Analog Out O3","","int16",""),
					array(33297,"R","Analog Out O4","","int16",""),
					array(33298,"R","Analog Out O5","","int16",""),
					array(33299,"R","Analog Out O6","","int16",""),
					array(33536,"R","Laufzeit Brennerstufe 1","","int16", "h"),
					array(33537,"R","Brennerstarts Stufe 1","","int16", "h"),
					array(33538,"R","Laufzeit Brennerstufe 2","","int16", "h"),
					array(33539,"R","Wärmeerzeuger SX aktuelle Leistung W","","int16",""),
					array(33540,"R","Ionisationsstrom mA","","int16","mA"),
					array(33792,"R","Meldungen Anzahl","","int16",""),
					array(33793,"R","Meldung 1 Code","","int16",""),
					array(33794,"R","Meldung 1 UnixZeit H","","int16",""),
					array(33795,"R","Meldung 1 UnixZeit L","","int16",""),
					array(33796,"R","Meldung 1 Par 1","","int16",""),
					array(33797,"R","Meldung 1 Par 2","","int16",""),
				);

				/*** Wechselrichter / Inverter ***/
				$categoryId = $parentId;

				// SmartMeter - Timer deaktivieren
//					$this->SetTimerInterval("SM_Update-Evt", 0);

				$this->createModbusInstances($modelRegister_array, $categoryId, $gatewayId, $pollCycle);


                if ($active) {
                    // Erreichbarkeit von IP und Port pruefen
                    $portOpen = false;
                    $waitTimeoutInSeconds = 1;
                    if ($fp = @fsockopen($hostIp, $hostPort, $errCode, $errStr, $waitTimeoutInSeconds)) {
                        // It worked
                        $portOpen = true;
                        fclose($fp);
    
                        // Client Soket aktivieren
                        if (false == IPS_GetProperty($interfaceId, "Open")) {
                            IPS_SetProperty($interfaceId, "Open", true);
                            IPS_ApplyChanges($interfaceId);
                            //IPS_Sleep(100);
                        }
                        
                        // aktiv
                        $this->SetStatus(102);
    
                        $this->SendDebug("Module-Status", MODUL_PREFIX."-module activated", 0);
                    } else {
                        // IP oder Port nicht erreichbar
                        $this->SetStatus(200);
    
                        $this->SendDebug("Module-Status", "ERROR: ".MODUL_PREFIX." with IP=".$hostIp." and Port=".$hostPort." cannot be reached!", 0);
                    }
                } else {
                    // Client Soket deaktivieren
                    if (true == IPS_GetProperty($interfaceId, "Open")) {
                        IPS_SetProperty($interfaceId, "Open", false);
                        IPS_ApplyChanges($interfaceId);
                        //IPS_Sleep(100);
                    }
                    
                    // Timer deaktivieren
                    /*
                                    $this->SetTimerInterval("Update-Autarkie-Eigenverbrauch", 0);
                                    $this->SetTimerInterval("Update-EMS-Status", 0);
                                    $this->SetTimerInterval("Update-WallBox_X_CTRL", 0);
                                    $this->SetTimerInterval("Update-ValuesKw", 0);
                                    $this->SetTimerInterval("Wh-Berechnung", 0);
                                    $this->SetTimerInterval("HistoryCleanUp", 0);
                    */
                    // inaktiv
                    $this->SetStatus(104);
    
                    $this->SendDebug("Module-Status", MODUL_PREFIX."-module deactivated", 0);
                }
    
                // pruefen, ob sich ModBus-Gateway geaendert hat
                if (0 != $gatewayId_Old && $gatewayId != $gatewayId_Old) {
                    $this->deleteInstanceNotInUse($gatewayId_Old, MODBUS_ADDRESSES);
                }

                // pruefen, ob sich ClientSocket Interface geaendert hat
                if (0 != $interfaceId_Old && $interfaceId != $interfaceId_Old) {
                    $this->deleteInstanceNotInUse($interfaceId_Old, MODBUS_INSTANCES);
                }
            }
		}

		private function createModbusInstances($modelRegister_array, $parentId, $gatewayId, $pollCycle, $uniqueIdent = "")
		{
			// Workaround für "InstanceInterface not available" Fehlermeldung beim Server-Start...
			if (KR_READY == IPS_GetKernelRunlevel())
			{
				// Erstelle Modbus Instancen
				foreach ($modelRegister_array as $inverterModelRegister)
				{
					$datenTyp = $this->getModbusDatatype($inverterModelRegister[IMR_TYPE]);
					if("continue" == $datenTyp)
					{
						continue;
					}

					$profile = $this->getProfile($inverterModelRegister[IMR_UNITS], $datenTyp);

					$instanceId = @IPS_GetObjectIDByIdent($inverterModelRegister[IMR_START_REGISTER].$uniqueIdent, $parentId);
					$initialCreation = false;

					// Modbus-Instanz erstellen, sofern noch nicht vorhanden
					if (false === $instanceId)
					{
						$this->SendDebug("create Modbus address", "REG_".$inverterModelRegister[IMR_START_REGISTER]." - ".$inverterModelRegister[IMR_NAME], 0);

						$instanceId = IPS_CreateInstance(MODBUS_ADDRESSES);

						IPS_SetParent($instanceId, $parentId);
						IPS_SetIdent($instanceId, $inverterModelRegister[IMR_START_REGISTER].$uniqueIdent);
						IPS_SetName($instanceId, $inverterModelRegister[IMR_NAME]);
						IPS_SetInfo($instanceId, $inverterModelRegister[IMR_DESCRIPTION]);

						$initialCreation = true;
					}

					// Gateway setzen
					if (IPS_GetInstance($instanceId)['ConnectionID'] != $gatewayId)
					{
						$this->SendDebug("set Modbus Gateway", "REG_".$inverterModelRegister[IMR_START_REGISTER]." - ".$inverterModelRegister[IMR_NAME]." --> GatewayID ".$gatewayId, 0);

						// sofern bereits eine Gateway verbunden ist, dieses trennen
						if (0 != IPS_GetInstance($instanceId)['ConnectionID'])
						{
							IPS_DisconnectInstance($instanceId);
						}

						// neues Gateway verbinden
						IPS_ConnectInstance($instanceId, $gatewayId);
					}


					// Modbus-Instanz konfigurieren
					if ($datenTyp != IPS_GetProperty($instanceId, "DataType"))
					{
						IPS_SetProperty($instanceId, "DataType", $datenTyp);
					}
					if (false != IPS_GetProperty($instanceId, "EmulateStatus"))
					{
						IPS_SetProperty($instanceId, "EmulateStatus", false);
					}
					if ($pollCycle != IPS_GetProperty($instanceId, "Poller"))
					{
						IPS_SetProperty($instanceId, "Poller", $pollCycle);
					}
/*
					if(0 != IPS_GetProperty($instanceId, "Factor"))
					{
						IPS_SetProperty($instanceId, "Factor", 0);
					}
*/

					// Read-Settings
					if ($inverterModelRegister[IMR_START_REGISTER] + MODBUS_REGISTER_TO_ADDRESS_OFFSET != IPS_GetProperty($instanceId, "ReadAddress"))
					{
						IPS_SetProperty($instanceId, "ReadAddress", $inverterModelRegister[IMR_START_REGISTER] + MODBUS_REGISTER_TO_ADDRESS_OFFSET);
					}
					if(6 == $inverterModelRegister[IMR_FUNCTION_CODE])
					{
						$ReadFunctionCode = 3;
					}
					else if("R" == $inverterModelRegister[IMR_FUNCTION_CODE])
					{
						$ReadFunctionCode = 3;
					}
					else if("RW" == $inverterModelRegister[IMR_FUNCTION_CODE])
					{
						$ReadFunctionCode = 3;
					}
					else
					{
						$ReadFunctionCode = $inverterModelRegister[IMR_FUNCTION_CODE];
					}

					if ($ReadFunctionCode != IPS_GetProperty($instanceId, "ReadFunctionCode"))
					{
						IPS_SetProperty($instanceId, "ReadFunctionCode", $ReadFunctionCode);
					}

					// Write-Settings
					if (4 < $inverterModelRegister[IMR_FUNCTION_CODE] && $inverterModelRegister[IMR_FUNCTION_CODE] != IPS_GetProperty($instanceId, "WriteFunctionCode"))
					{
						IPS_SetProperty($instanceId, "WriteFunctionCode", $inverterModelRegister[IMR_FUNCTION_CODE]);
					}

					if (4 < $inverterModelRegister[IMR_FUNCTION_CODE] && $inverterModelRegister[IMR_START_REGISTER] + MODBUS_REGISTER_TO_ADDRESS_OFFSET != IPS_GetProperty($instanceId, "WriteAddress"))
					{
						IPS_SetProperty($instanceId, "WriteAddress", $inverterModelRegister[IMR_START_REGISTER] + MODBUS_REGISTER_TO_ADDRESS_OFFSET);
					}

					if (0 != IPS_GetProperty($instanceId, "WriteFunctionCode"))
					{
						IPS_SetProperty($instanceId, "WriteFunctionCode", 0);
					}

					if(IPS_HasChanges($instanceId))
					{
						IPS_ApplyChanges($instanceId);
					}

					// Statusvariable der Modbus-Instanz ermitteln
					$varId = IPS_GetObjectIDByIdent("Value", $instanceId);

					// Profil der Statusvariable initial einmal zuweisen
					if ($initialCreation && false != $profile)
					{
						// Justification Rule 11: es ist die Funktion RegisterVariable...() in diesem Fall nicht nutzbar, da die Variable durch die Modbus-Instanz bereits erstellt wurde
						// --> Custo Profil wird initial einmal beim Instanz-erstellen gesetzt
						IPS_SetVariableCustomProfile($varId, $profile);
					}
				}
			}
		}
		
		private function getModbusDatatype($type)
		{
			// Datentyp ermitteln
			// 0=Bit, 1=Byte, 2=Word, 3=DWord, 4=ShortInt, 5=SmallInt, 6=Integer, 7=Real
			if ("uint16" == strtolower($type)
				|| "enum16" == strtolower($type)
				|| "uint8+uint8" == strtolower($type)
			)
			{
				$datenTyp = 2;
			}
			elseif ("uint32" == strtolower($type)
				|| "acc32" == strtolower($type)
				|| "acc64" == strtolower($type)
			)
			{
				$datenTyp = 3;
			}
			elseif ("int16" == strtolower($type)
				|| "sunssf" == strtolower($type)
			)
			{
				$datenTyp = 4;
			}
			elseif ("int32" == strtolower($type))
			{
				$datenTyp = 6;
			}
			elseif ("float32" == strtolower($type))
			{
				$datenTyp = 7;
			}
			elseif ("uint64" == strtolower($type))
			{
				$datenTyp = 8;
			}
			elseif ("string32" == strtolower($type)
				|| "string16" == strtolower($type)
				|| "string8" == strtolower($type)
				|| "string" == strtolower($type)
			)
			{
				$this->SendDebug("getModbusDatatype()", "Datentyp '".$type."' wird von Modbus in IPS nicht unterstützt! --> skip", 0);

				return "continue";
			}
			else
			{
				$this->SendDebug("getModbusDatatype()", "Unbekannter Datentyp '".$type."'! --> skip", 0);

				return "continue";
			}	

			return $datenTyp;
		}

		private function getProfile($unit, $datenTyp = -1)
		{
			// Profil ermitteln
			if ("a" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = "~Ampere";
			}
			elseif ("a" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Ampere.Int";
			}
			elseif (("ah" == strtolower($unit)
					|| "vah" == strtolower($unit))
				&& 7 == $datenTyp
			)
			{
						$profile = MODUL_PREFIX.".AmpereHour.Float";
			}
			elseif ("ah" == strtolower($unit)
				|| "vah" == strtolower($unit)
			)
			{
						$profile = MODUL_PREFIX.".AmpereHour.Int";
			}
			elseif ("v" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = "~Volt";
			}
			elseif("v" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Volt.Int";
			}
			elseif ("w" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = "~Watt.14490";
			}
			elseif ("w" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Watt.Int";
			}
			elseif ("hz" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = "~Hertz";
			}
			elseif ("hz" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Hertz.Int";
			}
			// Voltampere fuer elektrische Scheinleistung
			elseif ("va" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = MODUL_PREFIX.".Scheinleistung.Float";
			}
			// Voltampere fuer elektrische Scheinleistung
			elseif ("va" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Scheinleistung.Int";
			}
			// Var fuer elektrische Blindleistung
			elseif ("var" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = MODUL_PREFIX.".Blindleistung.Float";
			}
			// Var fuer elektrische Blindleistung
			elseif ("var" == strtolower($unit) || "var" == $unit)
			{
				$profile = MODUL_PREFIX.".Blindleistung.Int";
			}
			elseif ("%" == $unit && 7 == $datenTyp)
			{
				$profile = "~Valve.F";
			}
			elseif ("%" == $unit)
			{
				$profile = "~Valve";
			}
			elseif ("wh" == strtolower($unit) && (7 == $datenTyp || 8 == $datenTyp))
			{
				$profile = MODUL_PREFIX.".Electricity.Float";
			}
			elseif ("wh" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Electricity.Int";
			}
			elseif (("° C" == $unit
					|| "°C" == $unit
					|| "C" == $unit
				) && 7 == $datenTyp
			)
			{
				$profile = "~Temperature";
			}
			elseif ("° C" == $unit
				|| "°C" == $unit
				|| "C" == $unit
			)
			{
				$profile = MODUL_PREFIX.".Temperature.Int";
			}
			elseif ("cos()" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = MODUL_PREFIX.".Angle.Float";
			}
			elseif ("cos()" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Angle.Int";
			}
			elseif ("ohm" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Ohm.Int";
			}
			elseif ("enumerated_id" == strtolower($unit))
			{
				$profile = "SunSpec.ID.Int";
			}
			elseif ("enumerated_chast" == strtolower($unit))
			{
				$profile = "SunSpec.ChaSt.Int";
			}
			elseif ("enumerated_st" == strtolower($unit))
			{
				$profile = "SunSpec.StateCodes.Int";
			}
			elseif ("enumerated_stvnd" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".StateCodes.Int";
			}
			elseif ("" == $unit && "emergency-power" == strtolower($datenTyp))
			{
				$profile = MODUL_PREFIX.".Emergency-Power.Int";
			}
			elseif ("powermeter" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Powermeter.Int";
			}
			elseif ("secs" == strtolower($unit))
			{
				$profile = "~UnixTimestamp";
			}
			elseif ("registers" == strtolower($unit)
				|| "bitfield" == strtolower($unit)
				|| "bitfield16" == strtolower($unit)
				|| "bitfield32" == strtolower($unit)
			)
			{
				$profile = false;
			}
			else
			{
				$profile = false;
				if ("" != $unit)
				{
					$this->SendDebug("getProfile()", "ERROR: Profil '".$unit."' unbekannt!", 0);
				}
			}

			return $profile;			
		}


		private function checkProfiles()
		{
/*			$this->createVarProfile("SunSpec.ChaSt.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "N/A", 'Wert' => 0, "Unbekannter Status"),
					array('Name' => "OFF", 'Wert' => 1, "OFF: Energiespeicher nicht verfügbar"),
					array('Name' => "EMPTY", 'Wert' => 2, "EMPTY: Energiespeicher vollständig entladen"),
					array('Name' => "DISCHAGING", 'Wert' => 3, "DISCHARGING: Energiespeicher wird entladen"),
					array('Name' => "CHARGING", 'Wert' => 4, "CHARGING: Energiespeicher wird geladen"),
					array('Name' => "FULL", 'Wert' => 5, "FULL: Energiespeicher vollständig geladen"),
					array('Name' => "HOLDING", 'Wert' => 6, "HOLDING: Energiespeicher wird weder geladen noch entladen"),
					array('Name' => "TESTING", 'Wert' => 7, "TESTING: Energiespeicher wird getestet"),
				)
			);
			$this->createVarProfile("SunSpec.ID.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "single phase Inv (i)", 'Wert' => 101, "101: single phase Inverter (int)"),
					array('Name' => "split phase Inv (i)", 'Wert' => 102, "102: split phase Inverter (int)"),
					array('Name' => "three phase Inv (i)", 'Wert' => 103, "103: three phase Inverter (int)"),
					array('Name' => "single phase Inv (f)", 'Wert' => 111, "111: single phase Inverter (float)"),
					array('Name' => "split phase Inv (f)", 'Wert' => 112, "112: split phase Inverter (float)"),
					array('Name' => "three phase Inv (f)", 'Wert' => 113, "113: three phase Inverter (float)"),
					array('Name' => "single phase Meter (i)", 'Wert' => 201, "201: single phase Meter (int)"),
					array('Name' => "split phase Meter (i)", 'Wert' => 202, "202: split phase (int)"),
					array('Name' => "three phase Meter (i)", 'Wert' => 203, "203: three phase (int)"),
					array('Name' => "single phase Meter (f)", 'Wert' => 211, "211: single phase Meter (float)"),
					array('Name' => "split phase Meter (f)", 'Wert' => 212, "212: split phase Meter (float)"),
					array('Name' => "three phase Meter (f)", 'Wert' => 213, "213: three phase Meter (float)"),
					array('Name' => "string combiner (i)", 'Wert' => 403, "403: String Combiner (int)"),
				)
			);
			$this->createVarProfile("SunSpec.StateCodes.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "N/A", 'Wert' => 0, "Unbekannter Status"),
					array('Name' => "OFF", 'Wert' => 1, "Wechselrichter ist aus"),
					array('Name' => "SLEEPING", 'Wert' => 2, "Auto-Shutdown"),
					array('Name' => "STARTING", 'Wert' => 3, "Wechselrichter startet"),
					array('Name' => "MPPT", 'Wert' => 4, "Wechselrichter arbeitet normal", 'Farbe' => 65280),
					array('Name' => "THROTTLED", 'Wert' => 5, "Leistungsreduktion aktiv", 'Farbe' => 16744448),
					array('Name' => "SHUTTING_DOWN", 'Wert' => 6, "Wechselrichter schaltet ab"),
					array('Name' => "FAULT", 'Wert' => 7, "Ein oder mehr Fehler existieren, siehe St *oder Evt * Register", 'Farbe' => 16711680),
					array('Name' => "STANDBY", 'Wert' => 8, "Standby"),
				)
			);
			$this->createVarProfile(MODUL_PREFIX.".StateCodes.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "N/A", 'Wert' => 0, "Unbekannter Status"),
					array('Name' => "OFF", 'Wert' => 1, "Wechselrichter ist aus"),
					array('Name' => "SLEEPING", 'Wert' => 2, "Auto-Shutdown"),
					array('Name' => "STARTING", 'Wert' => 3, "Wechselrichter startet"),
					array('Name' => "MPPT", 'Wert' => 4, "Wechselrichter arbeitet normal", 'Farbe' => 65280),
					array('Name' => "THROTTLED", 'Wert' => 5, "Leistungsreduktion aktiv", 'Farbe' => 16744448),
					array('Name' => "SHUTTING_DOWN", 'Wert' => 6, "Wechselrichter schaltet ab"),
					array('Name' => "FAULT", 'Wert' => 7, "Ein oder mehr Fehler existieren, siehe St * oder Evt * Register", 'Farbe' => 16711680),
					array('Name' => "STANDBY", 'Wert' => 8, "Standby"),
					array('Name' => "NO_BUSINIT", 'Wert' => 9, "Keine SolarNet Kommunikation"),
					array('Name' => "NO_COMM_INV", 'Wert' => 10, "Keine Kommunikation mit Wechselrichter möglich"),
					array('Name' => "SN_OVERCURRENT", 'Wert' => 11, "Überstrom an SolarNet Stecker erkannt"),
					array('Name' => "BOOTLOAD", 'Wert' => 12, "Wechselrichter wird gerade upgedatet"),
					array('Name' => "AFCI", 'Wert' => 13, "AFCI Event (Arc-Erkennung)"),
				)
			);
/-*
			$this->createVarProfile(MODUL_PREFIX.".Emergency-Power.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "nicht unterstützt", 'Wert' => 0, "Notstrom wird nicht von Ihrem Gerät unterstützt", 'Farbe' => 16753920),
					array('Name' => "aktiv", 'Wert' => 1, "Notstrom aktiv (Ausfall des Stromnetzes)", 'Farbe' => 65280),
					array('Name' => "nicht aktiv", 'Wert' => 2, "Notstrom nicht aktiv", 'Farbe' => -1),
					array('Name' => "nicht verfügbar", 'Wert' => 3, "Notstrom nicht verfügbar", 'Farbe' => 16753920),
					array('Name' => "Fehler", 'Wert' => 4, "Der Motorschalter des S10 E befindet sich nicht in der richtigen Position, sondern wurde manuell abgeschaltet oder nicht eingeschaltet.", 'Farbe' => 16711680),
				)
			);

			$this->createVarProfile(MODUL_PREFIX.".Powermeter.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
				array('Name' => "N/A", 'Wert' => 0),
				array('Name' => "Wurzelleistungsmesser", 'Wert' => 1, "Dies ist der Regelpunkt des Systems. Der Regelpunkt entspricht üblicherweise dem Hausanschlusspunkt."),
				array('Name' => "Externe Produktion", 'Wert' => 2),
				array('Name' => "Zweirichtungszähler", 'Wert' => 3),
				array('Name' => "Externer Verbrauch", 'Wert' => 4),
				array('Name' => "Farm", 'Wert' => 5),
				array('Name' => "Wird nicht verwendet", 'Wert' => 6),
				array('Name' => "Wallbox", 'Wert' => 7),
				array('Name' => "Externer Leistungsmesser Farm", 'Wert' => 8),
				array('Name' => "Datenanzeige", 'Wert' => 9, "Wird nicht in die Regelung eingebunden, sondern dient nur der Datenaufzeichnung des Kundenportals."),
				array('Name' => "Regelungsbypass", 'Wert' => 10, "Die gemessene Leistung wird nicht in die Batterie geladen, aus der Batterie entladen."),
				)
			);
*-/
			$this->createVarProfile(MODUL_PREFIX.".Ampere.Int", VARIABLETYPE_INTEGER, ' A');
			$this->createVarProfile(MODUL_PREFIX.".AmpereHour.Float", VARIABLETYPE_FLOAT, ' Ah');
			$this->createVarProfile(MODUL_PREFIX.".AmpereHour.Int", VARIABLETYPE_INTEGER, ' Ah');
			$this->createVarProfile(MODUL_PREFIX.".Angle.Float", VARIABLETYPE_FLOAT, ' °');
			$this->createVarProfile(MODUL_PREFIX.".Angle.Int", VARIABLETYPE_INTEGER, ' °');
			$this->createVarProfile(MODUL_PREFIX.".Blindleistung.Float", VARIABLETYPE_FLOAT, ' Var');
			$this->createVarProfile(MODUL_PREFIX.".Blindleistung.Int", VARIABLETYPE_INTEGER, ' Var');
			$this->createVarProfile(MODUL_PREFIX.".Electricity.Float", VARIABLETYPE_FLOAT, ' Wh');
			$this->createVarProfile(MODUL_PREFIX.".Electricity.Int", VARIABLETYPE_INTEGER, ' Wh');
			$this->createVarProfile(MODUL_PREFIX.".Hertz.Int", VARIABLETYPE_INTEGER, ' Hz');
			$this->createVarProfile(MODUL_PREFIX.".Ohm.Int", VARIABLETYPE_INTEGER, ' Ohm');
			$this->createVarProfile(MODUL_PREFIX.".Scheinleistung.Float", VARIABLETYPE_FLOAT, ' VA');
			$this->createVarProfile(MODUL_PREFIX.".Scheinleistung.Int", VARIABLETYPE_INTEGER, ' VA');
			// Temperature.Float: ~Temperature
			$this->createVarProfile(MODUL_PREFIX.".Temperature.Int", VARIABLETYPE_INTEGER, ' °C');
			// Volt.Float: ~Volt
			$this->createVarProfile(MODUL_PREFIX.".Volt.Int", VARIABLETYPE_INTEGER, ' V');
			$this->createVarProfile(MODUL_PREFIX.".Watt.Int", VARIABLETYPE_INTEGER, ' W');
*/
		}

		private function GetVariableValue($instanceIdent, $variableIdent = "Value")
		{
			$instanceId = IPS_GetObjectIDByIdent($this->removeInvalidChars($instanceIdent), $this->InstanceID);
			$varId = IPS_GetObjectIDByIdent($this->removeInvalidChars($variableIdent), $instanceId);

			return GetValue($varId);
		}

		private function GetVariableId($instanceIdent, $variableIdent = "Value")
		{
			$instanceId = IPS_GetObjectIDByIdent($this->removeInvalidChars($instanceIdent), $this->InstanceID);
			$varId = IPS_GetObjectIDByIdent($this->removeInvalidChars($variableIdent), $instanceId);

			return $varId;
		}

		private function GetLoggedValuesInterval($id, $minutes)
		{
			$archiveId = IPS_GetInstanceListByModuleID("{43192F0B-135B-4CE7-A0A7-1475603F3060}");
			if (isset($archiveId[0]))
			{
				$archiveId = $archiveId[0];

				$returnValue = $this->getArithMittelOfLog($archiveId, $id, $minutes);
			}
			else
			{
				$archiveId = false;

				// no archive found
				$this->SetStatus(201);

				$returnValue = GetValue($id);
			}

			return $returnValue;
		}

	}
