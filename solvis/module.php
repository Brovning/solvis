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
	define("IMR_SIZE", 1);
	define("IMR_RW", 2);
	define("IMR_FUNCTION_CODE", 3);
	define("IMR_NAME", 4);
	define("IMR_DESCRIPTION", 5);
	define("IMR_TYPE", 6);
	define("IMR_UNITS", 7);
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

			// *** Inverter - Erstelle deaktivierte Timer ***
			// I11X model (Evt1, EvtVnd1, EvtVnd2, EvtVnd3)
			$this->RegisterTimer("Update-I11X", 0, "\$instanceId = IPS_GetObjectIDByIdent(\"40120\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"I_EVENT_GROUND_FAULT\", \"I_EVENT_DC_OVER_VOLT\", \"I_EVENT_AC_DISCONNECT\", \"I_EVENT_DC_DISCONNECT\", \"I_EVENT_GRID_DISCONNECT\", \"I_EVENT_CABINET_OPEN\", \"I_EVENT_MANUAL_SHUTDOWN\", \"I_EVENT_OVER_TEMP\", \"I_EVENT_OVER_FREQUENCY\", \"I_EVENT_UNDER_FREQUENCY\", \"I_EVENT_AC_OVER_VOLT\", \"I_EVENT_AC_UNDER_VOLT\", \"I_EVENT_BLOWN_STRING_FUSE\", \"I_EVENT_UNDER_TEMP\", \"I_EVENT_MEMORY_LOSS\", \"I_EVENT_HW_TEST_FAILURE\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

\$instanceId = IPS_GetObjectIDByIdent(\"40124\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"INSULATION_FAULT\", \"GRID_ERROR\", \"AC_OVERCURRENT\", \"DC_OVERCURRENT\", \"OVER_TEMP\", \"POWER_LOW\", \"DC_LOW\", \"INTERMEDIATE_FAULT\", \"FREQUENCY_HIGH\", \"FREQUENCY_LOW\", \"AC_VOLTAGE_HIGH\", \"AC_VOLTAGE_LOW\", \"DIRECT_CURRENT\", \"RELAY_FAULT\", \"POWER_STAGE_FAULT\", \"CONTROL_FAULT\", \"GC_GRID_VOLT_ERR\", \"GC_GRID_FREQU_ERR\", \"ENERGY_TRANSFER_FAULT\", \"REF_POWER_SOURCE_AC\", \"ANTI_ISLANDING_FAULT\", \"FIXED_VOLTAGE_FAULT\", \"MEMORY_FAULT\", \"DISPLAY_FAULT\", \"COMMUNICATION_FAULT\", \"TEMP_SENSORS_FAULT\", \"DC_AC_BOARD_FAULT\", \"ENS_FAULT\", \"FAN_FAULT\", \"DEFECTIVE_FUSE\", \"OUTPUT_CHOKE_FAULT\", \"CONVERTER_RELAY_FAULT\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

\$instanceId = IPS_GetObjectIDByIdent(\"40126\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"NO_SOLARNET_COMM\", \"INV_ADDRESS_FAULT\", \"NO_FEED_IN_24H\", \"PLUG_FAULT\", \"PHASE_ALLOC_FAULT\", \"GRID_CONDUCTOR_OPEN\", \"SOFTWARE_ISSUE\", \"POWER_DERATING\", \"JUMPER_INCORRECT\", \"INCOMPATIBLE_FEATURE\", \"VENTS_BLOCKED\", \"POWER_REDUCTION_ERROR\", \"ARC_DETECTED\", \"AFCI_SELF_TEST_FAILED\", \"CURRENT_SENSOR_ERROR\", \"DC_SWITCH_FAULT\", \"AFCI_DEFECTIVE\", \"AFCI_MANUAL_TEST_OK\", \"PS_PWR_SUPPLY_ISSUE\", \"AFCI_NO_COMM\", \"AFCI_MANUAL_TEST_FAILED\", \"AC_POLARITY_REVERSED\", \"FAULTY_AC_DEVICE\", \"FLASH_FAULT\", \"GENERAL_ERROR\", \"GROUNDING_ISSUE\", \"LIMITATION_FAULT\", \"OPEN_CONTACT\", \"OVERVOLTAGE_PROTECTION\", \"PROGRAM_STATUS\", \"SOLARNET_ISSUE\", \"SUPPLY_VOLTAGE_FAULT\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

\$instanceId = IPS_GetObjectIDByIdent(\"40128\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"TIME_FAULT\", \"USB_FAULT\", \"DC_HIGH\", \"INIT_ERROR\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

function removeInvalidChars(\$input)
{
	return preg_replace( '/[^a-z0-9]/i', '', \$input);
}");

			// IC120 model
			$this->RegisterTimer("Update-IC120", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC120 Nameplate")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40135, 40136), array(40137, 40138), array(40139, 40143), array(40140, 40143), array(40141, 40143), array(40142, 40143), array(40144, 40145), array(40146, 40150, \"cos()\"), array(40147, 40150, \"cos()\"), array(40148, 40150, \"cos()\"), array(40149, 40150, \"cos()\"), array(40151, 40152), array(40153, 40154), array(40155, 40156), array(40157, 40158));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}

	if(isset(\$inverterModelRegister[2]) && \"cos()\" == \$inverterModelRegister[2])
	{
		\$targetId = IPS_GetObjectIDByIdent(\"Value_cos\", \$instanceId);
		\$newValue = cos(\$newValue);

		if(GetValue(\$targetId) != \$newValue)
		{
			SetValue(\$targetId, \$newValue);
		}
	}
}");

			// IC121 model
			$this->RegisterTimer("Update-IC121", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC121 Basic Settings")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40162, 40182), array(40163, 40183), array(40164, 40184), array(40167, 40186), array(40168, 40187), array(40171, 40187), array(40173, 40189, \"cos()\"), array(40176, 40189, \"cos()\"));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}

	if(isset(\$inverterModelRegister[2]) && \"cos()\" == \$inverterModelRegister[2])
	{
		\$targetId = IPS_GetObjectIDByIdent(\"Value_cos\", \$instanceId);
		\$newValue = cos(\$newValue);

		if(GetValue(\$targetId) != \$newValue)
		{
			SetValue(\$targetId, \$newValue);
		}
	}
}");

			// IC122 model (PVConn, StorConn, StActCtl, Tms)
			$this->RegisterTimer("Update-IC122", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC122 Extended Measurements & Status")."\", ".$this->InstanceID.");
//PVConn
\$instanceId = IPS_GetObjectIDByIdent(\"40194\", \$parentId);
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"Connected\",  \"Responsive\", \"Operating\", \"Testing\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
	\$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

//StorConn
\$instanceId = IPS_GetObjectIDByIdent(\"40195\", \$parentId);
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"Connected\",  \"Responsive\", \"Operating\", \"Testing\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
	\$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

//StActCtl
\$instanceId = IPS_GetObjectIDByIdent(\"40227\", \$parentId);
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"FixedW - Leistungsreduktion\", \"FixedVAR - konstante Blindleistungs-Vorgabe\",  \"FixedPF - konstanter Power-Factor\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
	\$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

//Tms
\$instanceId = IPS_GetObjectIDByIdent(\"40233\", \$parentId);
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\"UTC\"), \$instanceId);
\$bitValue = \$varValue + 946681200;

if(GetValue(\$bitId) != \$bitValue)
{
	SetValue(\$bitId, \$bitValue);
}

function removeInvalidChars(\$input)
{
	return preg_replace( '/[^a-z0-9]/i', '', \$input);
}");

			// IC123 model
			$this->RegisterTimer("Update-IC123", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC123 Immediate Controls")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40243, 40261), array(40248, 40262, \"cos()\"));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}

	if(isset(\$inverterModelRegister[2]) && \"cos()\" == \$inverterModelRegister[2])
	{
		\$targetId = IPS_GetObjectIDByIdent(\"Value_cos\", \$instanceId);
		\$newValue = cos(\$newValue);

		if(GetValue(\$targetId) != \$newValue)
		{
			SetValue(\$targetId, \$newValue);
		}
	}
}");

			// IC124 model
			$this->RegisterTimer("Update-IC124", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC124 Basic Storage Control")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40316, 40332), array(40317, 40333), array(40318, 40333), array(40321, 40335), array(40322, 40336), array(40323, 40337), array(40324, 40338), array(40326, 40339), array(40327, 40339));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}
}");
			// I160 model
			$this->RegisterTimer("Update-I160", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("I160 Multiple MPPT Inverter Extension")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40283, 40266), array(40284, 40267), array(40285, 40268), array(40286, 40269), 
array(40303, 40266), array(40304, 40267), array(40305, 40268), array(40306, 40269));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}
}");

			// *** SmartMeter - Erstelle deaktivierte Timer ***
			// Evt
			$this->RegisterTimer("SM_Update-Evt", 0, "\$instanceId = IPS_GetObjectIDByIdent(\"40194\".\"SmartMeter\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"LOW_VOLTAGE\", \"LOW_POWER\", \"LOW_EFFICIENCY\", \"CURRENT\", \"VOLTAGE\", \"POWER\", \"PR\", \"DISCONNECTED\", \"FUSE_FAULT\", \"COMBINER_FUSE_FAULT\", \"COMBINER_CABINET_OPEN\", \"TEMP\", \"GROUNDFAULT\", \"REVERSED_POLARITY\", \"INCOMPATIBLE\", \"COMM_ERROR\", \"INTERNAL_ERROR\", \"THEFT\", \"ARC_DETECTED\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

function removeInvalidChars(\$input)
{
	return preg_replace( '/[^a-z0-9]/i', '', \$input);
}");

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


			}

		}

		private function createModbusInstances($inverterModelRegister_array, $parentId, $gatewayId, $pollCycle, $uniqueIdent = "")
		{
			// Workaround für "InstanceInterface not available" Fehlermeldung beim Server-Start...
			if (KR_READY == IPS_GetKernelRunlevel())
			{
				// Erstelle Modbus Instancen
				foreach ($inverterModelRegister_array as $inverterModelRegister)
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
			$this->createVarProfile("SunSpec.ChaSt.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
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
/*
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
*/
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
